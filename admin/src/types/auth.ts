export interface User {
  uuid: string
  name: string
  email: string
  phone: string | null
  role: string | null
  is_active: boolean
  created_at: string | null
}

export interface LoginResponse {
  success: boolean
  message: string
  data: {
    access_token: string
    token_type: string
    user: User
  }
}