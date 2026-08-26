import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import type { User } from '../types/auth'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const accessToken = ref<string | null>(null)

  const isAuthenticated = computed(() => {
    return accessToken.value !== null && user.value !== null
  })

  const role = computed(() => {
    return user.value?.role ?? null
  })

  const setAuth = (token: string, authenticatedUser: User) => {
    accessToken.value = token
    user.value = authenticatedUser

    localStorage.setItem('access_token', token)
    localStorage.setItem('user', JSON.stringify(authenticatedUser))
  }
  const initializeAuth = () => {
    const storedToken = localStorage.getItem('access_token')
    const storedUser = localStorage.getItem('user')

    if (storedToken && storedUser) {
      try {
        accessToken.value = storedToken
        user.value = JSON.parse(storedUser) as User
      } catch {
        accessToken.value = null
        user.value = null

        localStorage.removeItem('access_token')
        localStorage.removeItem('user')
      }
    }
  }
  const logout = () => {
    accessToken.value = null
    user.value = null

    localStorage.removeItem('access_token')
    localStorage.removeItem('user')
  }

  return {
    user,
    accessToken,
    isAuthenticated,
    role,
    setAuth,
    initializeAuth,
    logout,
  }
})