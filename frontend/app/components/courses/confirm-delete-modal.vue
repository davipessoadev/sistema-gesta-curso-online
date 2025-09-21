<script setup lang="ts">
import { defineProps, defineEmits } from "vue";
import type { Course } from "~/pages/courses/index.vue";

const props = defineProps<{
  open: boolean;
  course: Course | null;
}>();

const emit = defineEmits<{
  (e: "update:open", value: boolean): void;
  (e: "confirm"): void;
}>();

function onConfirm() {
  emit("confirm");
  emit("update:open", false);
}

function onClose() {
  emit("update:open", false);
}
</script>

<template>
  <UModal v-model:open="props.open">
    <template #content>
      <div class="p-4">
        <h3 class="text-lg font-semibold mb-2">Confirmação de deleção</h3>
        <p>
          Tem certeza que deseja deletar
          <strong>{{ props.course?.name }}</strong
          >?
        </p>

        <div class="mt-4 flex justify-end gap-2">
          <UButton color="neutral" @click="onClose">Cancelar</UButton>
          <UButton color="error" @click="onConfirm">Deletar</UButton>
        </div>
      </div>
    </template>
  </UModal>
</template>
