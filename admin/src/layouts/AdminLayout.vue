<script setup lang="ts">
import AppSidebar from '../components/AppSidebar.vue'
import AppHeader from '../components/AppHeader.vue'
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <AppSidebar />

    <div class="ml-64">
      <AppHeader />

      <main class="p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>