<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { useToast } from "#imports";
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
} from "chart.js";
import { Bar } from "vue-chartjs";

ChartJS.register(
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
);

type CourseStats = {
  id: string;
  name: string;
  student_count: number;
};

const totalCourses = ref(0);
const totalStudents = ref(0);
const courses = ref<CourseStats[]>([]);

const config = useRuntimeConfig();
const toast = useToast();

async function fetchStats() {
  try {
    const dataCourses = await $fetch(`${config.public.apiBase}/courses?page=1`);
    const dataStudents = await $fetch(
      `${config.public.apiBase}/students?page=1`
    );

    totalCourses.value = dataCourses.total;
    totalStudents.value = dataStudents.total;

    courses.value = dataCourses.data.map((course: any) => ({
      id: course.id,
      name: course.name,
      student_count: course.students?.length || 0,
    }));
  } catch {
    toast.add({ title: "Erro ao carregar estatísticas", color: "error" });
  }
}

onMounted(fetchStats);

// Computed para criar chartData reativo
const chartData = computed(() => ({
  labels: courses.value.map((c) => c.name),
  datasets: [
    {
      label: "Alunos",
      data: courses.value.map((c) => c.student_count),
      backgroundColor: "#3b82f6",
    },
  ],
}));

const chartOptions = {
  responsive: true,
  plugins: { legend: { display: false } },
};
</script>

<template>
  <div class="space-y-6 p-4">
    <div class="grid grid-cols-2 gap-4">
      <UCard class="p-4">
        <h3 class="text-lg font-semibold">Total de Cursos</h3>
        <p class="text-2xl font-bold">{{ totalCourses }}</p>
      </UCard>
      <UCard class="p-4">
        <h3 class="text-lg font-semibold">Total de Alunos</h3>
        <p class="text-2xl font-bold">{{ totalStudents }}</p>
      </UCard>
    </div>

    <UCard class="p-4">
      <h3 class="text-lg font-semibold mb-4">Alunos por Curso</h3>
      <Bar v-if="courses.length" :data="chartData" :options="chartOptions" />
      <p v-else class="text-gray-500">Nenhum curso encontrado.</p>
    </UCard>
  </div>
</template>
