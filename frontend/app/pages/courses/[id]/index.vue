<script setup lang="ts">
import { ref } from "vue";
import type { Course } from "../index.vue";
import type { Student } from "~/pages/students/index.vue";
import CourseStudentTable from "~/components/courses/course-student-table.vue";

const route = useRoute();
const config = useRuntimeConfig();
const toast = useToast();

const courseId = route.params.id as string;

export type StudentsWithPivot = Student & {
  pivot: {
    id: number;
    course_id: number;
    student_id: number;
    progress_percentage: number;
    enrollment_date: string;
  };
};

const course = ref<Course | null>(null);
const students = ref<StudentsWithPivot[]>([]);

async function fetchCourse() {
  const { data } = await useFetch<Course>(
    `${config.public.apiBase}/courses/${courseId}`
  );
  if (data.value) {
    course.value = data.value;
  }
}

async function fetchStudents() {
  const { data } = await useFetch<StudentsWithPivot[]>(
    `${config.public.apiBase}/courses/${courseId}/students`
  );
  if (data.value) {
    students.value = data.value;
  }
}

onMounted(async () => {
  await fetchCourse();
  await fetchStudents();
});

async function updateProgress(student: StudentsWithPivot, newProgress: number) {
  const { error } = await useFetch(
    `${config.public.apiBase}/enrollments/${student.pivot.id}/progress`,
    {
      method: "PATCH",
      body: { progress_percentage: newProgress },
    }
  );

  if (error.value) {
    toast.add({ title: "Erro ao atualizar progresso", color: "error" });
  } else {
    student.pivot.progress_percentage = newProgress;
    toast.add({ title: "Progresso atualizado!", color: "success" });
  }
}
</script>

<template>
  <div class="space-y-6 p-4 md:p-6">
    <UCard v-if="course">
      <template #header>
        <h2 class="text-xl font-semibold">{{ course.name }}</h2>
        <p class="text-sm text-gray-500">
          Criado em
          {{ new Date(course.created_at).toLocaleDateString("pt-BR") }}
        </p>
      </template>
      <div class="space-y-2">
        <p><strong>Descrição:</strong> {{ course.description }}</p>
        <p><strong>Duração:</strong> {{ course.duration_hours }} horas</p>
      </div>
    </UCard>

    <CourseStudentTable
      :students="students"
      :courseId="courseId"
      :onUpdateProgress="updateProgress"
    />
  </div>
</template>
