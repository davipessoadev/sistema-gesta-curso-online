<script setup lang="ts">
import { reactive, watch } from "vue";
import { z } from "zod";
import type { FormSubmitEvent } from "@nuxt/ui";
import { vMaska } from "maska/vue";

const props = defineProps<{
  student?: { name: string; email: string; cpf: string };
}>();

const emit = defineEmits<{
  (e: "submit", data: { name: string; email: string; cpf: string }): void;
}>();

// Validação Zod
const schema = z.object({
  name: z.string().min(1, "Nome é obrigatório"),
  email: z.string().email("E-mail inválido"),
  cpf: z
    .string()
    .min(14, "CPF deve ter 11 dígitos")
    .regex(/^\d{3}\.\d{3}\.\d{3}-\d{2}$/, "CPF inválido"),
});

const state = reactive({
  name: props.student?.name || "",
  email: props.student?.email || "",
  cpf: props.student?.cpf || "",
});

// Atualiza state se `student` mudar
watch(
  () => props.student,
  (newStudent) => {
    if (newStudent) {
      state.name = newStudent.name;
      state.email = newStudent.email;
      state.cpf = newStudent.cpf;
    }
  }
);

function onSubmit(event: FormSubmitEvent<(typeof schema)["_input"]>) {
  emit("submit", { ...event.data });
}
</script>

<template>
  <UCard>
    <template #header>
      <h2 class="text-xl font-semibold">
        {{ props.student ? "Editar Aluno" : "Cadastrar Aluno" }}
      </h2>
    </template>

    <UForm
      :schema="schema"
      :state="state"
      @submit.prevent="onSubmit"
      class="space-y-4"
    >
      <UFormField label="Nome" name="name" required>
        <UInput
          v-model="state.name"
          placeholder="Nome completo do aluno"
          class="w-full"
          @keydown.enter.prevent
        />
      </UFormField>

      <UFormField label="E-mail" name="email" required>
        <UInput
          type="email"
          v-model="state.email"
          placeholder="exemplo@email.com"
          class="w-full"
          @keydown.enter.prevent
        />
      </UFormField>

      <UFormField label="CPF" name="cpf" required>
        <UInput
          v-model="state.cpf"
          v-maska="'###.###.###-##'"
          placeholder="000.000.000-00"
          class="w-full"
        />
      </UFormField>

      <div class="flex justify-end">
        <UButton type="submit" color="primary" icon="i-lucide-save">
          Salvar
        </UButton>
      </div>
    </UForm>
  </UCard>
</template>

<script lang="ts">
export default {
  directives: {
    maska: vMaska,
  },
};
</script>
