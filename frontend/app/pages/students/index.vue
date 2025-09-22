<script setup lang="ts">
import { resolveComponent, ref, watch } from "vue";
import type { TableColumn } from "@nuxt/ui";

const config = useRuntimeConfig();
const UButton = resolveComponent("UButton");
const toast = useToast();

const filterOptions = ref([
  {
    value: "name",
    label: "Nome",
  },
  {
    value: "email",
    label: "Email",
  },
  {
    value: "cpf",
    label: "CPF",
  },
]);
const filterValue = ref("name");
const filterSearch = ref("");

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

async function fetchStudents(pageNumber = 1) {
  const params = new URLSearchParams();
  params.append("page", pageNumber.toString());

  if (filterSearch.value) {
    params.append("field", filterValue.value);
    params.append("value", filterSearch.value);
  }

  const { data, error } = await useFetch<ApiStudentsResponse>(
    `${config.public.apiBase}/students?page=${pageNumber}?${params.toString()}`
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
];
</script>

<template>
  <div class="flex flex-col gap-4">
    <div
      class="flex flex-col md:flex-row md:items-center md:justify-between mb-4"
    >
      <div class="mb-4 md:mb-0">
        <h1 class="text-2xl font-bold">Alunos Cadastrados</h1>
        <p class="text-sm text-gray-500">
          Lista de todos os alunos registrados no sistema, com CPF e e-mail.
        </p>
      </div>

      <NuxtLink to="/students/create">
        <UButton color="primary" icon="i-lucide-plus"> Novo Aluno </UButton>
      </NuxtLink>
    </div>

    <div class="flex flex-col md:flex-row md:items-center gap-2">
      <USelect
        v-model="filterValue"
        :items="filterOptions"
        class="w-full md:w-30"
      />
      <UInput v-model="filterSearch" class="w-full" />

      <UButton
        icon="i-lucide-search"
        size="md"
        color="neutral"
        variant="solid"
        class="w-full md:w-auto"
        @click="() => fetchStudents(1)"
        >Buscar</UButton
      >
    </div>

    <div class="overflow-x-auto w-full">
      <UTable
        :data="students"
        :columns="columns"
        class="min-w-full table-auto"
      />
    </div>

    <UPagination
      v-model:page="page"
      :total="total"
      class="flex justify-center"
    />
  </div>
</template>
