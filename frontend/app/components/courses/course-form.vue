<script setup lang="ts">
import { reactive, toRefs, watch } from "vue";
import { z } from "zod";
import type { FormSubmitEvent } from "@nuxt/ui";

const props = defineProps<{
  course?: { name: string; description: string; duration_hours: number };
}>();
const emit = defineEmits<{
  (
    e: "submit",
    data: { name: string; description: string; duration_hours: number }
  ): void;
}>();

const schema = z.object({
  name: z.string().min(1, "Nome é obrigatório"),
  description: z.string().optional(),
  duration_hours: z.number().min(1, "Duração mínima de 1 hora"),
});

const state = reactive({
  name: props.course?.name || "",
  description: props.course?.description || "",
  duration_hours: props.course?.duration_hours || 1,
});

watch(
  () => props.course,
  (newCourse) => {
    if (newCourse) {
      state.name = newCourse.name;
      state.description = newCourse.description;
      state.duration_hours = newCourse.duration_hours;
    }
  }
);

function onSubmit(event: FormSubmitEvent<(typeof schema)["_input"]>) {
  emit("submit", {
    ...event.data,
    description: event.data.description ?? "",
  });
}
</script>

<template>
  <UCard>
    <template #header>
      <h2 class="text-xl font-semibold">
        {{ props.course ? "Editar Curso" : "Criar Curso" }}
      </h2>
    </template>

    <UForm :schema="schema" :state="state" @submit="onSubmit" class="space-y-4">
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
</template>
