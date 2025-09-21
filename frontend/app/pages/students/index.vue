<script setup lang="ts">
import { h, resolveComponent, ref, watch } from "vue";
import type { TableColumn } from "@nuxt/ui";
import type { Row } from "@tanstack/vue-table";

const config = useRuntimeConfig();
const UDropdownMenu = resolveComponent("UDropdownMenu");
const UButton = resolveComponent("UButton");
const toast = useToast();

export type Student = {
  id: string;
  name: string;
  email: string;
  cpf: string;
  created_at: string;
};

type ApiStudentsResponse = {
  data: Student[];
  current_page: number;
  total: number;
};

const page = ref(1);
const students = ref<Student[]>([]);
const total = ref(0);

const showDeleteModal = ref(false);
const studentToDelete = ref<Student | null>(null);

async function fetchStudents(pageNumber = 1) {
  const { data } = await useFetch<ApiStudentsResponse>(
    `${config.public.apiBase}/students?page=${pageNumber}`
  );

  if (data.value) {
    students.value = data.value.data;
    total.value = data.value.total;
    page.value = data.value.current_page;
  }
}

await fetchStudents(page.value);

watch(page, (newPage, oldPage) => {
  if (newPage !== oldPage) {
    fetchStudents(newPage);
  }
});

const columns: TableColumn<Student>[] = [
  { accessorKey: "id", header: "#" },
  { accessorKey: "name", header: "Nome" },
  { accessorKey: "email", header: "E-mail" },
  { accessorKey: "cpf", header: "CPF" },
  {
    accessorKey: "created_at",
    header: "Criado em",
    cell: ({ row }) =>
      new Date(row.getValue("created_at")).toLocaleDateString("pt-BR", {
        day: "2-digit",
        month: "short",
        year: "numeric",
      }),
  },
  {
    id: "actions",
    cell: ({ row }) =>
      h(
        "div",
        { class: "text-right" },
        h(
          UDropdownMenu,
          {
            content: { align: "end" },
            items: getRowItems(row),
            "aria-label": "Ações do estudante",
          },
          () =>
            h(UButton, {
              icon: "i-lucide-ellipsis-vertical",
              color: "neutral",
              variant: "ghost",
              class: "ml-auto",
              "aria-label": "Ações do estudante",
            })
        )
      ),
  },
];

function getRowItems(row: Row<Student>) {
  return [
    { type: "label", label: "Ações" },
    {
      label: "Ver detalhes",
      to: `/students/${row.original.id}`,
      icon: "i-lucide-eye",
    },
    {
      label: "Editar estudante",
      icon: "i-lucide-edit",
      to: `/students/${row.original.id}/edit`,
    },
    {
      label: "Deletar estudante",
      icon: "i-lucide-trash",
      onSelect() {
        studentToDelete.value = row.original;
        showDeleteModal.value = true;
      },
    },
  ];
}

async function confirmDelete() {
  if (!studentToDelete.value) return;
  try {
    const { error } = await useFetch(
      `${config.public.apiBase}/students/${studentToDelete.value.id}`,
      { method: "DELETE" }
    );

    if (error.value) {
      toast.add({
        title: "Erro ao deletar estudante",
        description: error.value.message,
        color: "error",
      });
    } else {
      students.value = students.value.filter(
        (s) => s.id !== studentToDelete.value?.id
      );
      toast.add({ title: "Estudante deletado com sucesso!", color: "success" });
    }
  } finally {
    showDeleteModal.value = false;
    studentToDelete.value = null;
  }
}
</script>

<template>
  <div class="flex flex-col gap-4">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h1 class="text-2xl font-bold">Estudantes Cadastrados</h1>
        <p class="text-sm text-gray-500">
          Lista de todos os estudantes registrados no sistema, com CPF e e-mail.
        </p>
      </div>

      <NuxtLink to="/students/create">
        <UButton color="primary" icon="i-lucide-plus"> Novo Estudante </UButton>
      </NuxtLink>
    </div>

    <div class="overflow-x-auto w-full">
      <UTable :data="students" :columns="columns" class="w-full table-auto" />
    </div>

    <UPagination v-model:page="page" :total="total" />
  </div>
</template>
