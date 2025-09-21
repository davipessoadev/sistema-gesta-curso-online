<script setup lang="ts">
import type { NavigationMenuItem } from "@nuxt/ui";

const items = ref<NavigationMenuItem[][]>([
  [
    {
      label: "Dashboard",
      icon: "i-lucide-layout-dashboard",
      type: "link",
      to: "/",
    },
    {
      label: "Cursos",
      icon: "i-lucide-book-open",
      type: "link",
      to: "/courses",
    },
    {
      label: "Alunos",
      icon: "i-lucide-users",
      type: "link",
      to: "/students",
    },
  ],
]);

const isSidebarOpen = ref(false);

const toggleSidebar = () => {
  isSidebarOpen.value = !isSidebarOpen.value;
};
</script>

<template>
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside
      :class="[
        'w-64 border-r border-border border-slate-800 bg-default transition-transform duration-300 ease-in-out',
        'fixed top-0 left-0 h-full z-40',
        isSidebarOpen ? 'translate-x-0' : '-translate-x-full',
        'md:translate-x-0 md:static',
      ]"
    >
      <div class="p-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-text-primary">School Admin</h1>
        <UButton
          icon="i-lucide-x"
          variant="ghost"
          color="secondary"
          class="md:hidden"
          @click="toggleSidebar"
        />
      </div>
      <UNavigationMenu
        orientation="vertical"
        :items="items"
        class="data-[orientation=vertical]:w-full border-border border-slate-800 bg-default h-[calc(100vh-64px)]"
        :default-class="['text-text-secondary hover:text-primary']"
      />
    </aside>

    <!-- Overlay for mobile when sidebar is open -->
    <div
      v-if="isSidebarOpen"
      class="fixed z-30 md:hidden"
      @click="toggleSidebar"
    />

    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Header with toggle button -->
      <header class="border-b border-border border-slate-800 p-4 md:p-6">
        <UButton
          icon="i-lucide-menu"
          variant="ghost"
          color="secondary"
          class="md:hidden"
          @click="toggleSidebar"
        />
      </header>
      <main class="flex-1 p-6">
        <slot />
      </main>
    </div>
  </div>
</template>
