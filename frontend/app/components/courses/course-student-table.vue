<script setup lang="ts">
import { h, ref } from "vue";
import type { StudentsWithPivot } from "~/pages/courses/[id]/index.vue";
import EnrollStudentModal from "./enroll-students-modal.vue";
const route = useRoute();

const config = useRuntimeConfig();

const courseId = route.params.id as string;

const props = defineProps<{
  students: StudentsWithPivot[];
  courseId: string;
  onUpdateProgress: (
    student: StudentsWithPivot,
    newProgress: number
  ) => Promise<void>;
}>();

const showEnrollModal = ref(false);

function onProgressChange(student: StudentsWithPivot, value: number) {
  props.onUpdateProgress(student, value);
}

async function fetchStudents() {
  const { data } = await useFetch<StudentsWithPivot[]>(
    `${config.public.apiBase}/courses/${courseId}/students`
  );
  if (data.value)
    props.students.splice(0, props.students.length, ...data.value);
}
</script>

<template>
  <UCard>
    <template #header>
      <div class="flex flex-col md:flex-row md:justify-between md:items-center">
        <h3 class="text-lg font-semibold mb-2 md:mb-0">Alunos Matriculados</h3>

        <UButton
          color="primary"
          icon="i-lucide-user-plus"
          @click="showEnrollModal = true"
        >
          Matricular aluno
        </UButton>

        <EnrollStudentModal
          v-model:open="showEnrollModal"
          :course-id="courseId"
          @enrolled="fetchStudents"
        />
      </div>
    </template>

    <div v-if="props.students.length > 0" class="overflow-x-auto mt-2">
      <UTable
        :data="props.students"
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
                    onProgressChange(row.original, val);
                  }
                },
              });
            }
          },
        ]"
        class="min-w-full table-auto"
      />
    </div>

    <p v-else class="text-gray-500 text-sm mt-2">
      Nenhum aluno matriculado ainda.
    </p>
  </UCard>
</template>
