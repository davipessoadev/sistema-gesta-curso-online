<script setup lang="ts">
import StudentForm from "~/components/students/student-form.vue";

const config = useRuntimeConfig();
const router = useRouter();
const toast = useToast();

async function handleSubmit(data: {
  name: string;
  email: string;
  cpf: string;
}) {
  const { error } = await useFetch(`${config.public.apiBase}/students`, {
    method: "POST",
    body: data,
  });

  if (error.value) {
    toast.add({
      title: "Erro ao cadastrar aluno",
      description: error.value.data.message,
      color: "error",
    });
  } else {
    toast.add({
      title: "Aluno cadastrado com sucesso!",
      color: "success",
    });

    router.push("/students");
  }
}
</script>

<template>
  <div class="mx-auto">
    <StudentForm @submit="handleSubmit" />
  </div>
</template>
