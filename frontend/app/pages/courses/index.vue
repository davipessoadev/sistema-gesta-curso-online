<script setup lang="ts">
import { h, resolveComponent, ref, watch } from "vue";
import type { TableColumn } from "@nuxt/ui";
import type { Row } from "@tanstack/vue-table";
import ConfirmDeleteModal from "~/components/courses/confirm-delete-modal.vue";

const config = useRuntimeConfig();
const UDropdownMenu = resolveComponent("UDropdownMenu");
const UButton = resolveComponent("UButton");
const toast = useToast();

export type Course = {
  id: string;
  name: string;
  description: string;
  duration_hours: number;
  created_at: string;
};

type ApiCoursesResponse = {
  data: Course[];
  current_page: number;
  total: number;
};

const page = ref(1);
const courses = ref<Course[]>([]);
const total = ref(0);

const showDeleteModal = ref(false);
const courseToDelete = ref<Course | null>(null);

async function fetchCourses(pageNumber = 1) {
  const { data } = await useFetch<ApiCoursesResponse>(
    `${config.public.apiBase}/courses?page=${pageNumber}`
  );

  if (data.value) {
    courses.value = data.value.data;
    total.value = data.value.total;
    page.value = data.value.current_page;
  }
}

await fetchCourses(page.value);

watch(page, (newPage, oldPage) => {
  if (newPage !== oldPage) {
    fetchCourses(newPage);
  }
});

const columns: TableColumn<Course>[] = [
  {
    accessorKey: "id",
    header: "#",
    cell: ({ row }) => `${row.getValue("id")}`,
  },
  { accessorKey: "name", header: "Nome" },
  {
    accessorKey: "description",
    header: "Descrição",
    cell: ({ row }) => {
      const desc = row.getValue("description") as string;
      return desc.length > 50 ? desc.substring(0, 50) + "…" : desc;
    },
  },
  { accessorKey: "duration_hours", header: "Duração (h)" },
  {
    accessorKey: "created_at",
    header: "Criado em",
    cell: ({ row }) => {
      return new Date(row.getValue("created_at")).toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "short",
        year: "numeric",
      });
    },
  },
  {
    id: "actions",
    cell: ({ row }) => {
      return h(
        "div",
        { class: "text-right" },
        h(
          UDropdownMenu,
          {
            content: { align: "end" },
            items: getRowItems(row),
            "aria-label": "Ações do curso",
          },
          () =>
            h(UButton, {
              icon: "i-lucide-ellipsis-vertical",
              color: "neutral",
              variant: "ghost",
              class: "ml-auto",
              "aria-label": "Ações do curso",
            })
        )
      );
    },
  },
];

function getRowItems(row: Row<Course>) {
  return [
    { type: "label", label: "Ações" },
    {
      label: "Ver detalhes",
      to: `/courses/${row.original.id}`,
      icon: "i-lucide-eye",
    },
    {
      label: "Editar curso",
      icon: "i-lucide-edit",
      to: `/courses/${row.original.id}/edit`,
    },
    {
      label: "Deletar curso",
      icon: "i-lucide-trash",
      onSelect() {
        courseToDelete.value = row.original;
        showDeleteModal.value = true;
      },
    },
  ];
}

async function confirmDelete() {
  if (!courseToDelete.value) return;
  try {
    const { error } = await useFetch(
      `${config.public.apiBase}/courses/${courseToDelete.value.id}`,
      { method: "DELETE" }
    );

    if (error.value) {
      toast.add({
        title: "Erro ao deletar curso",
        description: error.value.message,
        color: "error",
      });
    } else {
      courses.value = courses.value.filter(
        (c) => c.id !== courseToDelete.value?.id
      );

      toast.add({ title: "Curso deletado com sucesso!", color: "success" });
    }
  } finally {
    showDeleteModal.value = false;
    courseToDelete.value = null;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h1 class="text-2xl font-bold">Cursos Disponíveis</h1>
        <p class="text-sm text-gray-500">
          Lista de todos os cursos cadastrados no sistema, com duração e
          descrição resumida.
        </p>
      </div>

      <NuxtLink to="/courses/create">
        <UButton color="primary" icon="i-lucide-plus"> Novo Curso </UButton>
      </NuxtLink>
    </div>

    <div class="overflow-x-auto w-full">
      <UTable :data="courses" :columns="columns" class="w-full table-auto" />
    </div>

    <UPagination v-model:page="page" :total="total" />

    <ConfirmDeleteModal
      v-model:open="showDeleteModal"
      :course="courseToDelete"
      @confirm="confirmDelete"
    />
  </div>
</template>
