<script setup lang="ts">
import { ref, onMounted } from 'vue'

const router = useRouter()
const route = useRoute()

const email = ref('')
const token = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const isLoading = ref(false)
const error = ref('')
const success = ref('')

const { resetPassword } = useAuth()

onMounted(() => {
  // Get token from URL query parameter
  token.value = (route.query.token as string) || ''
  email.value = (route.query.email as string) || ''

  if (!token.value) {
    error.value = 'Invalid reset link. Please request a new password reset.'
  }
})

const handleResetPassword = async () => {
  if (!password.value || !passwordConfirmation.value) {
    error.value = 'Please fill in all fields'
    return
  }

  if (password.value !== passwordConfirmation.value) {
    error.value = 'Passwords do not match'
    return
  }

  if (password.value.length < 8) {
    error.value = 'Password must be at least 8 characters'
    return
  }

  isLoading.value = true
  error.value = ''
  success.value = ''

  try {
    await resetPassword(token.value, password.value, passwordConfirmation.value, email.value)
    success.value = 'Password has been reset successfully! Redirecting to login...'

    // Redirect to login after 2 seconds
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } catch (err: any) {
    console.error('Reset password error:', err)
    error.value = err.message || 'Failed to reset password. The link may have expired.'
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

        <h2 class="card-title text-2xl mb-2">Reset Password</h2>
        <p class="text-base-content/70 text-sm mb-6">
          Enter your new password below.
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

        <!-- Password Input -->
        <div class="form-control w-full mb-4">
          <label class="label">
            <span class="label-text font-semibold">New Password</span>
          </label>
          <input
            v-model="password"
            type="password"
            placeholder="Enter new password"
            class="input input-bordered w-full"
            :disabled="isLoading || !token"
          />
          <label class="label">
            <span class="label-text-alt">At least 8 characters</span>
          </label>
        </div>

        <!-- Password Confirmation Input -->
        <div class="form-control w-full mb-6">
          <label class="label">
            <span class="label-text font-semibold">Confirm Password</span>
          </label>
          <input
            v-model="passwordConfirmation"
            type="password"
            placeholder="Confirm new password"
            class="input input-bordered w-full"
            :disabled="isLoading || !token"
            @keyup.enter="handleResetPassword"
          />
        </div>

        <!-- Submit Button -->
        <button
          @click="handleResetPassword"
          class="btn btn-primary w-full"
          :disabled="isLoading || !token"
        >
          <span v-if="isLoading" class="loading loading-spinner loading-sm"></span>
          <span v-else>Reset Password</span>
        </button>
      </div>
    </div>
  </div>
</template>
