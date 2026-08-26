<script setup lang="ts">
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
</script>

<template>
  <header class="h-16 bg-white border-b border-gray-200">
    <div class="h-full px-6 flex items-center justify-between">

      <div>
        <h2 class="text-lg font-semibold text-gray-900">
          Admin Dashboard
        </h2>
      </div>

      <div class="flex items-center gap-4">
        <div class="text-right">
          <p class="text-sm font-medium text-gray-900">
            {{ authStore.user?.name }}
          </p>

          <p class="text-xs text-gray-500 capitalize">
            {{ authStore.role }}
          </p>
        </div>

        <button
          class="rounded-lg px-3 py-2 text-sm text-gray-600 hover:bg-gray-100"
          @click="authStore.logout"
        >
          Logout
        </button>
      </div>

    </div>
  </header>
</template>