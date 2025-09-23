<script lang="ts" setup>
import { useStudentsStore } from "~/stores/students";

const filterOptions = [
  { value: "name", label: "Nome" },
  { value: "email", label: "Email" },
  { value: "cpf", label: "CPF" },
];

const studentsStore = useStudentsStore();

const filterValue = ref("name");
const filterSearch = ref("");

async function fetchStudents() {
  const params = new URLSearchParams();
  if (filterSearch.value) {
    params.append("field", filterValue.value);
    params.append("value", filterSearch.value);
  }

  await studentsStore.fetchStudents({
    pageNumber: 1,
    queryParams: params.toString(),
  });
}
</script>

<template>
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
      @click="() => fetchStudents()"
    >
      Buscar
    </UButton>
  </div>
</template>
