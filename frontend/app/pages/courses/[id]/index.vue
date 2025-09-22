<script setup lang="ts">
import { ref, h } from "vue";
import type { Course } from "../index.vue";
import type { Student } from "~/pages/students/index.vue";

const route = useRoute();
const config = useRuntimeConfig();
const toast = useToast();

type StudentsWithPivot = Student & {
  pivot: {
    id: number
    course_id: number
    student_id: number
    progress_percentage: number
    enrollment_date: string
  }
}

const course = ref<Course | null>(null);
const students = ref<StudentsWithPivot[]>([]);

// Buscar dados do curso
const { data: courseData, error: courseError } = await useFetch<Course>(
  `${config.public.apiBase}/courses/${route.params.id}`
);

if (courseError.value) {
  toast.add({ title: "Erro ao carregar curso", color: "error" });
} else {
  if (courseData.value) {
    course.value = courseData.value;

  }
}

// Buscar alunos matriculados
const { data: studentsData, error: studentsError } = await useFetch<StudentsWithPivot[]>(
  `${config.public.apiBase}/courses/${route.params.id}/students`
);

if (studentsError.value) {
  toast.add({ title: "Erro ao carregar alunos", color: "error" });
} else {
  students.value = studentsData.value ?? [];
}

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

async function enrollStudent(studentId: string) {
  const { error } = await useFetch(
    `${config.public.apiBase}/courses/${route.params.id}/students`,
    {
      method: "POST",
      body: { student_id: studentId },
    }
  );

  if (!error.value) {
    const { data } = await useFetch<StudentsWithPivot[]>(
      `${config.public.apiBase}/courses/${route.params.id}/students`
    );
    students.value = data.value ?? [];
    toast.add({ title: "Aluno matriculado!", color: "success" });
  }
}
</script>

<template>
  <div class="space-y-6">
    <UCard>
      <template #header>
        <h2 class="text-xl font-bold">{{ course?.name }}</h2>
      </template>
      <p class="text-gray-600">{{ course?.description }}</p>
      <p class="mt-2"><strong>Duração:</strong> {{ course?.duration_hours }} horas</p>
      <p class="text-sm text-gray-400 mt-1">
        <div v-if="course">
          Criado em: {{ new Date(course.created_at).toLocaleDateString("pt-BR") }}
        </div>
      </p>
    </UCard>

    <UCard>
      <template #header>
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold">Alunos matriculados</h3>
          <UButton
            color="primary"
            icon="i-lucide-user-plus"
            @click="() => enrollStudent('student_id_fake')"
          >
            Matricular aluno
          </UButton>
        </div>
      </template>

      <div v-if="students.length > 0" class="overflow-x-auto">
        <UTable
          :data="students"
          :columns="[
            { accessorKey: 'id', header: '#' },
            { accessorKey: 'name', header: 'Nome' },
            { accessorKey: 'email', header: 'Email' },
            { accessorKey: 'cpf', header: 'CPF' },
            {
              accessorKey: 'pivot.progress_percentage',
              header: 'Progresso (%)',
              cell: ({ row }) => {
                return h('input', {
                  type: 'number',
                  min: 0,
                  max: 100,
                  value: row.original.pivot.progress_percentage,
                  class: 'border rounded w-20 text-center',
                  onInput: (e: Event) => {
                    const val = parseInt((e.target as HTMLInputElement).value, 10);
                    if (!isNaN(val) && val >= 0 && val <= 100) {
                      updateProgress(row.original, val);
                    }
                  },
                });
              }
            },
          ]"
          class="w-full table-auto"
        />
      </div>
      <p v-else class="text-gray-500 text-sm">
        Nenhum aluno matriculado ainda.
      </p>
    </UCard>
  </div>
</template>
