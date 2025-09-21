<script setup lang="ts">
import { h, resolveComponent, ref, watch } from "vue";
import type { TableColumn } from "@nuxt/ui";
import type { Row } from "@tanstack/vue-table";

const config = useRuntimeConfig();
const UDropdownMenu = resolveComponent("UDropdownMenu");
const UButton = resolveComponent("UButton");
const toast = useToast();

type Course = {
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

// Função do menu de ações
function getRowItems(row: Row<Course>) {
  return [
    { type: "label", label: "Ações" },
    {
      label: "Copiar ID do curso",
      onSelect() {
        navigator.clipboard.writeText(row.original.id);
        toast.add({
          title: "ID do curso copiado!",
          color: "success",
          icon: "i-lucide-circle-check",
        });
      },
    },
    { type: "separator" },
    {
      label: "Ver detalhes",
      onSelect() {
        toast.add({
          title: `Visualizando curso: ${row.original.name}`,
          color: "info",
        });
      },
    },
    {
      label: "Editar curso",
      onSelect() {
        toast.add({
          title: `Editando curso: ${row.original.name}`,
          color: "warning",
        });
      },
    },
    {
      label: "Deletar curso",
      onSelect() {
        toast.add({
          title: `Curso deletado: ${row.original.name}`,
          color: "error",
        });
      },
    },
  ];
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <!-- Header -->
    <div class="mb-4">
      <h1 class="text-2xl font-bold">Cursos Disponíveis</h1>
      <p class="text-sm text-gray-500">
        Lista de todos os cursos cadastrados no sistema, com duração e descrição
        resumida.
      </p>
    </div>

    <!-- Tabela responsiva -->
    <div class="overflow-x-auto w-full">
      <UTable :data="courses" :columns="columns" class="w-full table-auto" />
    </div>

    <!-- Paginação -->
    <UPagination v-model:page="page" :total="total" />
  </div>
</template>
