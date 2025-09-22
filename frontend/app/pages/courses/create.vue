<script setup lang="ts">
import { useRouter } from "vue-router";
import { useRuntimeConfig } from "#app";
import CourseForm from "~/components/courses/course-form.vue";

const router = useRouter();
const config = useRuntimeConfig();
const toast = useToast();

async function handleSubmit(formData: {
  name: string;
  description: string;
  duration_hours: number;
}) {
  const { error } = await useFetch(`${config.public.apiBase}/courses`, {
    method: "POST",
    body: formData,
  });

  if (error.value) {
    toast.add({
      title: "Erro ao criar curso",
      description: error.value.data.message,
      color: "error",
    });
  } else {
    toast.add({ title: "Curso criado com sucesso!", color: "success" });
    router.push("/courses");
  }
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Novo Curso</h1>
    <CourseForm @submit="handleSubmit" />
  </div>
</template>
