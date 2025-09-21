<script setup lang="ts">
import { ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { useRuntimeConfig } from "#app";
import type { Course } from "../index.vue";
import CourseForm from "~/components/courses/course-form.vue";

const route = useRoute();
const router = useRouter();
const config = useRuntimeConfig();

const courseId = route.params.id as string;

const course = ref<Course | null>(null);
const loading = ref(true);
const toast = useToast();

const { data, error } = await useFetch<Course>(
  `${config.public.apiBase}/courses/${courseId}`
);

if (data.value) {
  course.value = data.value;
  loading.value = false;
}

async function handleSubmit(formData: {
  name: string;
  description: string;
  duration_hours: number;
}) {
  const { error } = await useFetch(
    `${config.public.apiBase}/courses/${courseId}`,
    {
      method: "PUT",
      body: formData,
    }
  );

  if (error.value) {
    toast.add({
      title: "Erro ao atualizar curso",
      description: error.value.data.message,
      color: "error",
    });
  } else {
    toast.add({ title: "Curso atualizado com sucesso!", color: "success" });
    router.push("/courses");
  }
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Editar Curso</h1>

    <div v-if="loading">Carregando curso...</div>
    <div v-else-if="!course">Curso não encontrado.</div>
    <div v-else>
      <CourseForm :course="course" @submit="handleSubmit" />
    </div>
  </div>
</template>
