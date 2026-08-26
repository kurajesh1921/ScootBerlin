import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
  history: createWebHistory(),

  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/auth/Login.vue'),
      meta: {
        guestOnly: true,
      },
    },

    {
      path: '/',
      component: () => import('../layouts/AdminLayout.vue'),
      meta: {
        requiresAuth: true,
      },
      children: [
        {
          path: '',
          redirect: '/dashboard',
        },
        {
          path: 'dashboard',
          name: 'dashboard',
          component: () => import('../views/Dashboard.vue'),
        },
      ],
    },
  ],
})

router.beforeEach((to) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return {
      name: 'login',
    }
  }

  if (to.meta.guestOnly && authStore.isAuthenticated) {
    return {
      name: 'dashboard',
    }
  }

  return true
})

export default router