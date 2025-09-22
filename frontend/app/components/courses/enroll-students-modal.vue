<script setup lang="ts">
import { ref } from "vue";
import { useFetch, useToast } from "#imports";
import { z } from "zod";

const config = useRuntimeConfig();

const props = defineProps<{
  open: boolean;
  courseId: string;
}>();

const emit = defineEmits<{
  (e: "update:open", value: boolean): void;
  (e: "enrolled"): void; // Apenas um evento para atualizar a lista
}>();

const toast = useToast();
const state = ref({ student_id: "" });

const schema = z.object({
  student_id: z.string().min(1, "Aluno é obrigatório"),
});

function onClose() {
  emit("update:open", false);
}

async function onSubmit() {
  const payload = { ...state.value, course_id: props.courseId };

  const { error } = await useFetch(`${config.public.apiBase}/enrollments`, {
    method: "POST",
    body: payload,
  });

  if (!error.value) {
    emit("update:open", false);

    state.value.student_id = "";

    toast.add({ title: "Aluno matriculado com sucesso!", color: "success" });

    emit("enrolled");
  } else {
    toast.add({ title: "Erro ao matricular aluno", color: "error" });
  }
}
</script>

<template>
  <UModal v-model:open="props.open">
    <template #content>
      <div class="p-4">
        <h3 class="text-lg font-semibold mb-2">Matricular Aluno</h3>
        <p>Digite o ID do aluno abaixo</p>

        <UForm
          :schema="schema"
          :state="state"
          @submit.prevent="onSubmit"
          class="flex gap-2 flex-col mt-4"
        >
          <UFormField name="student_id" required>
            <UInput
              class="w-full"
              v-model="state.student_id"
              placeholder="ID do aluno"
            />
          </UFormField>

          <div class="mt-4 flex justify-end gap-2">
            <UButton color="neutral" @click="onClose">Cancelar</UButton>
            <UButton type="submit" color="primary">Matricular</UButton>
          </div>
        </UForm>
      </div>
    </template>
  </UModal>
</template>
