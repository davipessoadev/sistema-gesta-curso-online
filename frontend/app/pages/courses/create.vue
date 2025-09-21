<script setup lang="ts">
import { z } from "zod";
import type { FormSubmitEvent } from "@nuxt/ui";
import { reactive } from "vue";
import { useToast } from "#imports";

const config = useRuntimeConfig();
const toast = useToast();

const schema = z.object({
  name: z.string().min(1, "O nome do curso é obrigatório"),
  description: z.string().nullable(),
  duration_hours: z
    .number({ error: "Duração deve ser um número" })
    .min(1, "A duração deve ser pelo menos 1 hora"),
});

type Schema = z.infer<typeof schema>;

const state = reactive<Schema>({
  name: "",
  description: "",
  duration_hours: 1,
});

async function onSubmit(event: FormSubmitEvent<Schema>) {
  try {
    const { error } = await useFetch(`${config.public.apiBase}/courses`, {
      method: "POST",
      body: event.data,
    });

    if (error.value) {
      toast.add({
        title: "Erro ao criar curso",
        description: error.value.data.message,
        color: "error",
      });
    } else {
      toast.add({
        title: "Curso criado com sucesso!",
        color: "success",
      });
      navigateTo("/courses");
    }
  } catch (err) {
    toast.add({
      title: "Erro inesperado",
      description: String(err),
      color: "error",
    });
  }
}
</script>

<template>
  <div class="mx-auto">
    <UCard>
      <template #header>
        <h2 class="text-xl font-semibold">Criar Novo Curso</h2>
      </template>

      <UForm
        :schema="schema"
        :state="state"
        @submit="onSubmit"
        class="space-y-4"
      >
        <UFormField label="Nome do Curso" name="name" required>
          <UInput
            v-model="state.name"
            placeholder="Ex: Fundamentos de JavaScript"
            class="w-full"
          />
        </UFormField>

        <UFormField label="Descrição" name="description">
          <UTextarea
            v-model="state.description"
            placeholder="Breve descrição do curso..."
            class="w-full"
          />
        </UFormField>

        <UFormField label="Duração (horas)" name="duration_hours" required>
          <UInput type="number" v-model="state.duration_hours" min="1" />
        </UFormField>

        <div class="flex justify-end">
          <UButton type="submit" color="primary" icon="i-lucide-save">
            Salvar
          </UButton>
        </div>
      </UForm>
    </UCard>
  </div>
</template>
