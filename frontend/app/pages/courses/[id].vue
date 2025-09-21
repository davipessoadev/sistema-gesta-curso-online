<script setup lang="ts">
import type { Course } from "./index.vue";

const route = useRoute();
const config = useRuntimeConfig();

const courseId = route.params.id as string;

const {
  data: course,
  pending,
  error,
} = await useFetch<Course>(`${config.public.apiBase}/courses/${courseId}`);
</script>

<template>
  <h1 class="text-2xl font-bold mb-4">Detalhes do Curso</h1>

  <div v-if="pending">Carregando curso...</div>
  <div v-else-if="error">Erro ao carregar curso.</div>
  <div v-else-if="course">
    <UCard>
      <template #header>
        <h2 class="text-xl font-semibold">{{ course.name }}</h2>
        <p class="text-sm text-gray-500">
          Criado em
          {{ new Date(course.created_at).toLocaleDateString("pt-BR") }}
        </p>
      </template>

      <div class="space-y-4">
        <div>
          <p class="text-sm font-medium text-gray-500">ID</p>
          <p class="text-base">{{ course.id }}</p>
        </div>

        <div>
          <p class="text-sm font-medium text-gray-500">Descrição</p>
          <p class="text-base">{{ course.description }}</p>
        </div>

        <div>
          <p class="text-sm font-medium text-gray-500">Duração</p>
          <p class="text-base">{{ course.duration_hours }} horas</p>
        </div>
      </div>
    </UCard>
  </div>
</template>
