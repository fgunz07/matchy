export const useAuth = () => {
    const { get, post } = useApi()

    interface CurrentUser {
        id: number
        name: string
        email: string
        avatar_url: string | null
        bio: string | null
        gender: string
        birthday: string
    }

    interface LoginPayload {
        email: string
        password: string
    }

    interface RegisterPayload {
        name: string
        email: string
        password: string
        password_confirmation: string
        gender: string
        birthday: string
    }

    interface AuthResponse {
        token: string
        user: CurrentUser
    }

    interface GoogleAuthResponse {
        url: string
    }

    const storeCurrentUser = (user: CurrentUser): void => {
        if (import.meta.client) {
            localStorage.setItem('current_user', JSON.stringify(user))
        }
    }

    const login = async (payload: LoginPayload): Promise<AuthResponse> => {
        try {
            const response = await post('/auth/login', payload)
            const data = response.data as AuthResponse

            // Store token in localStorage
            if (data.token) {
                localStorage.setItem('auth_token', data.token)
            }

            // Store current user
            if (data.user) {
                storeCurrentUser(data.user)
            }

            return data
        } catch (error) {
            console.error('Login error:', error)
            throw error
        }
    }

    const google = async (): Promise<GoogleAuthResponse> => {
        try {
            const response = await get('/auth/social/google')
            const data = response as GoogleAuthResponse
            return data
        } catch (error) {
            console.error('Login error:', error)
            throw error
        }
    }

    const register = async (payload: RegisterPayload): Promise<AuthResponse> => {
        try {
            const response = await post('/auth/register', payload)
            const data = response as AuthResponse

            // Store token in localStorage
            if (data.token) {
                localStorage.setItem('auth_token', data.token)
            }

            // Store current user
            if (data.user) {
                storeCurrentUser(data.user)
            }

            return data
        } catch (error) {
            console.error('Registration error:', error)
            throw error
        }
    }

    const logout = (): void => {
        // Remove token and user from localStorage
        localStorage.removeItem('auth_token')
        localStorage.removeItem('current_user')
    }

    const getAuthToken = (): string | null => {
        if (import.meta.client) {
            return localStorage.getItem('auth_token')
        }
        return null
    }

    const getCurrentUser = (): CurrentUser | null => {
        if (import.meta.client) {
            const user = localStorage.getItem('current_user')
            return user ? JSON.parse(user) : null
        }
        return null
    }

    const fetchCurrentUser = async (): Promise<CurrentUser> => {
        try {
            const response = await get('/me')
            const user = response.data || response
            storeCurrentUser(user)
            return user
        } catch (error) {
            console.error('Failed to fetch current user:', error)
            throw error
        }
    }

    const isAuthenticated = (): boolean => {
        return !!getAuthToken()
    }

    const updateCurrentUser = async (userId: number, userData: { name?: string; email?: string; bio?: string; gender?: string; birthday?: string; password?: string; password_confirmation?: string }): Promise<CurrentUser> => {
        try {
            const response = await useApi().patch(`/users/${userId}`, userData)
            const user = response.data || response
            storeCurrentUser(user)
            return user
        } catch (error) {
            console.error('Failed to update user:', error)
            throw error
        }
    }

    const forgetPassword = async (email: string): Promise<void> => {
        try {
            await post('/auth/forget-password', { email })
        } catch (error) {
            console.error('Forget password error:', error)
            throw error
        }
    }

    const resetPassword = async (token: string, password: string, passwordConfirmation: string, email: string): Promise<void> => {
        try {
            await post('/auth/reset-password', {
                email,
                token,
                password,
                password_confirmation: passwordConfirmation,
            })
        } catch (error) {
            console.error('Reset password error:', error)
            throw error
        }
    }

    return {
        login,
        google,
        register,
        logout,
        getAuthToken,
        getCurrentUser,
        fetchCurrentUser,
        isAuthenticated,
        updateCurrentUser,
        forgetPassword,
        resetPassword,
    }
}
