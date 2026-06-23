<script setup lang="ts">
import { ref } from 'vue'
import { useForm } from 'laravel-precognition-vue';

const router = useRouter()
const email = ref('')
const password = ref('')
const isLoading = ref(false)
const error = ref('')

const { login, google } = useAuth()

const handleLogin = async () => {
  isLoading.value = true
  error.value = ''
  try {
    await login({
      email: email.value,
      password: password.value,
    })
    // Redirect to discover page on success
    await router.push('/discover')
  } catch (err: any) {
    console.error('Login error:', err)
    error.value = err.message || 'Login failed. Please try again.'
  } finally {
    isLoading.value = false
  }
}

const handleGoogleLogin = async () => {
    const { url } = await google()
    window.open(url, '_self', 'noopener,noreferrer')
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-base-100 p-4">
    <div class="card w-full max-w-md bg-base-200 shadow-xl">
      <div class="card-body">
        <p class="text-center text-base-content/70 mb-6">
          Sign in to your account
        </p>

        <!-- Error Message -->
        <div v-if="error" class="alert alert-error mb-4">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="stroke-current shrink-0 h-6 w-6"
            fill="none"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M10 14l4-4m0 0l4-4m-4 4l-4-4m4 4l4 4M3 12a9 9 0 1118 0 9 9 0 01-18 0z"
            />
          </svg>
          <span>{{ error }}</span>
        </div>

        <!-- Google Login Button -->
        <button
          @click="handleGoogleLogin"
          type="button"
          class="btn btn-outline btn-lg w-full mb-4 gap-2"
        >
          <svg
            class="w-5 h-5"
            viewBox="0 0 24 24"
            fill="currentColor"
          >
            <path
              d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
            />
            <path
              d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
            />
            <path
              d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
            />
            <path
              d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
            />
          </svg>
          Continue with Google
        </button>

        <!-- Divider -->
        <div class="divider">OR</div>

        <!-- Email Input -->
        <div class="form-control mb-4">
          <label class="label">
            <span class="label-text font-semibold">Email Address</span>
          </label>
          <input
            v-model="email"
            type="email"
            placeholder="you@example.com"
            class="input input-bordered w-full"
            required
          />
        </div>

        <!-- Password Input -->
        <div class="form-control mb-2">
          <label class="label">
            <span class="label-text font-semibold">Password</span>
          </label>
          <input
            v-model="password"
            type="password"
            placeholder="Enter your password"
            class="input input-bordered w-full"
            required
          />
        </div>

        <!-- Forgot Password Link -->
        <div class="mb-6">
          <NuxtLink
            to="/forget-password"
            class="link link-hover text-sm text-primary"
          >
            Forgot password?
          </NuxtLink>
        </div>

        <!-- Login Button -->
        <button
          @click="handleLogin"
          :disabled="isLoading || !email || !password"
          class="btn btn-primary btn-lg w-full"
        >
          <span v-if="isLoading" class="loading loading-spinner"></span>
          <span v-else>Sign In</span>
        </button>

        <!-- Register Link -->
        <div class="text-center mt-6">
          <p class="text-base-content/70">
            Don't have an account?
            <NuxtLink to="/register" class="link link-primary font-semibold">
              Register here
            </NuxtLink>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>