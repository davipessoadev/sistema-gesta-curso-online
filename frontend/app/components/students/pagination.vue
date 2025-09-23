<script setup lang="ts">
import { useStudentsStore } from "~/stores/students";

const studentsStore = useStudentsStore();

watch(
  () => studentsStore.page,
  (newPage, oldPage) => {
    if (newPage !== oldPage) {
      studentsStore.fetchStudents({ pageNumber: newPage });
    }
  }
);
</script>

<template>
  <UPagination
    v-model:page="studentsStore.page"
    :total="studentsStore.total"
    class="flex justify-center"
  />
</template>
