<script setup lang="ts">
import { ref } from 'vue'

const router = useRouter()
const email = ref('')
const isLoading = ref(false)
const error = ref('')
const success = ref('')

const { forgetPassword } = useAuth()

const handleForgetPassword = async () => {
  if (!email.value) {
    error.value = 'Please enter your email address'
    return
  }

  isLoading.value = true
  error.value = ''
  success.value = ''

  try {
    await forgetPassword(email.value)
    success.value = 'Password reset link has been sent to your email. Please check your inbox.'
    email.value = ''

    // Redirect to login after 3 seconds
    setTimeout(() => {
      router.push('/login')
    }, 3000)
  } catch (err: any) {
    console.error('Forget password error:', err)
    error.value = err.message || 'Failed to send password reset link. Please try again.'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-base-100 p-4">
    <div class="card w-full max-w-md bg-base-200 shadow-xl">
      <div class="card-body">
        <!-- Back to Login -->
        <div class="mb-4">
          <NuxtLink to="/login" class="link link-primary text-sm">
            ← Back to Login
          </NuxtLink>
        </div>

        <h2 class="card-title text-2xl mb-2">Forgot Password?</h2>
        <p class="text-base-content/70 text-sm mb-6">
          Enter your email address and we'll send you a link to reset your password.
        </p>

        <!-- Success Message -->
        <div v-if="success" class="alert alert-success mb-4">
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
              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
          <span>{{ success }}</span>
        </div>

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
              d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m-2-2l-2 2"
            ></path>
          </svg>
          <span>{{ error }}</span>
        </div>

        <!-- Email Input -->
        <div class="form-control w-full mb-4">
          <label class="label">
            <span class="label-text font-semibold">Email</span>
          </label>
          <input
            v-model="email"
            type="email"
            placeholder="your@email.com"
            class="input input-bordered w-full"
            :disabled="isLoading"
            @keyup.enter="handleForgetPassword"
          />
        </div>

        <!-- Submit Button -->
        <button
          @click="handleForgetPassword"
          class="btn btn-primary w-full mb-3"
          :disabled="isLoading"
        >
          <span v-if="isLoading" class="loading loading-spinner loading-sm"></span>
          <span v-else>Send Reset Link</span>
        </button>

        <!-- Sign Up Link -->
        <div class="text-center text-sm">
          <span class="text-base-content/70">Don't have an account? </span>
          <NuxtLink to="/register" class="link link-primary font-semibold">
            Sign up
          </NuxtLink>
        </div>
      </div>
    </div>
  </div>
</template>
