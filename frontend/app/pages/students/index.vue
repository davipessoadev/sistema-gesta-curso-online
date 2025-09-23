<script setup lang="ts">
import { watch, onMounted } from "vue";
import type { TableColumn } from "@nuxt/ui";
import { useStudentsStore, type Student } from "~/stores/students";
import Pagination from "~/components/students/pagination.vue";
import SearchFilter from "~/components/students/search-filter.vue";

const studentsStore = useStudentsStore();

async function fetchStudents(pageNumber = 1) {
  await studentsStore.fetchStudents({
    pageNumber,
  });
}

onMounted(() => {
  fetchStudents(studentsStore.current_page);
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
      <div>
        <h1 class="text-2xl font-bold">Alunos Cadastrados</h1>
        <p class="text-sm text-gray-500">
          Lista de todos os alunos registrados no sistema, com CPF e e-mail.
        </p>
      </div>

      <NuxtLink to="/students/create">
        <UButton color="primary" icon="i-lucide-plus"> Novo Aluno </UButton>
      </NuxtLink>
    </div>

    <SearchFilter />

    <div class="overflow-x-auto w-full">
      <UTable
        v-if="!studentsStore.loading"
        :data="studentsStore.students"
        :columns="columns"
        class="min-w-full table-auto"
      />

      <div v-else class="flex flex-col gap-2">
        <USkeleton class="h-10 w-full" v-for="n in 5" :key="n" />
      </div>
    </div>

    <Pagination />
  </div>
</template>
