<script setup lang="ts">
import {
  LayoutDashboard,
  Bike,
  Users,
  CalendarDays,
  MapPin,
  Wrench,
  CreditCard,
  ClipboardList,
  LogOut,
} from 'lucide-vue-next'

import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()

const menuItems = [
  {
    label: 'Dashboard',
    to: '/dashboard',
    icon: LayoutDashboard,
  },
  {
    label: 'Scooters',
    to: '/scooters',
    icon: Bike,
  },
  {
    label: 'Riders',
    to: '/riders',
    icon: Users,
  },
  {
    label: 'Reservations',
    to: '/reservations',
    icon: CalendarDays,
  },
  {
    label: 'Rides',
    to: '/rides',
    icon: MapPin,
  },
  {
    label: 'Maintenance',
    to: '/maintenance',
    icon: Wrench,
  },
  {
    label: 'Payments',
    to: '/payments',
    icon: CreditCard,
  },
  {
    label: 'Audit Logs',
    to: '/audit-logs',
    icon: ClipboardList,
  },
]
</script>

<template>
  <aside class="fixed inset-y-0 left-0 w-64 bg-gray-900 text-white">
    <!-- Logo -->
    <div class="flex h-16 items-center px-6 border-b border-gray-800">
      <h1 class="text-xl font-bold">
        ScootBerlin
      </h1>
    </div>

    <!-- Navigation -->
    <nav class="p-4">
      <p class="px-3 text-xs font-semibold uppercase text-gray-500">
        Menu
      </p>

      <div class="mt-3 space-y-1">
        <router-link
          v-for="item in menuItems"
          :key="item.to"
          :to="item.to"
          class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white"
          active-class="bg-gray-800 text-white"
        >
          <component
            :is="item.icon"
            :size="18"
          />

          <span>{{ item.label }}</span>
        </router-link>
      </div>
    </nav>

    <!-- Logout -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-800">
      <button
        type="button"
        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm text-gray-300 hover:bg-gray-800 hover:text-white"
        @click="authStore.logout"
      >
        <LogOut :size="18" />

        <span>Logout</span>
      </button>
    </div>
  </aside>
</template>