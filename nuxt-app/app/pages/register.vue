<script setup lang="ts">
import { ref } from 'vue'

interface FormData {
  name: string
  email: string
  gender: string
  birthday: string
  bio: string
  password: string
  passwordConfirmation: string
}

const router = useRouter()
const { register } = useAuth()

const formData = ref<FormData>({
  name: '',
  email: '',
  gender: '',
  birthday: '',
  bio: '',
  password: '',
  passwordConfirmation: '',
})

const isLoading = ref(false)
const error = ref('')
const success = ref(false)

const genderOptions = [
  { value: '', label: 'Select gender' },
  { value: 'male', label: 'Male' },
  { value: 'female', label: 'Female' },
  { value: 'other', label: 'Other' },
]

const passwordsMatch = () => {
  return formData.value.password === formData.value.passwordConfirmation
}

const isPasswordStrong = () => {
  return formData.value.password.length >= 8
}

const isFormValid = () => {
  return (
    formData.value.name &&
    formData.value.email &&
    formData.value.gender &&
    formData.value.birthday &&
    formData.value.password &&
    formData.value.passwordConfirmation &&
    passwordsMatch() &&
    isPasswordStrong()
  )
}

const handleRegister = async () => {
  isLoading.value = true
  error.value = ''
  success.value = false

  if (!isFormValid()) {
    error.value = 'Please fill in all fields correctly'
    isLoading.value = false
    return
  }

  try {
    await register({
      name: formData.value.name,
      email: formData.value.email,
      gender: formData.value.gender,
      birthday: formData.value.birthday,
      password: formData.value.password,
      password_confirmation: formData.value.passwordConfirmation,
    })
    success.value = true
    // Redirect to discover page on success
    setTimeout(() => {
      router.push('/login')
    }, 1500)
  } catch (err: any) {
    console.error('Registration error:', err)
    error.value = err.message || 'Registration failed. Please try again.'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-base-100 p-4 py-8">
    <div class="card w-full max-w-2xl bg-base-200 shadow-xl">
      <div class="card-body">
        <!-- Header -->
        <h1 class="card-title text-2xl font-bold text-center mb-2">
          Create Account
        </h1>
        <p class="text-center text-base-content/70 mb-6">
          Join us and start connecting
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
            />
          </svg>
          <span>Account created successfully!</span>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleRegister" class="space-y-4">
          <!-- Name Input -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Full Name</span>
            </label>
            <input
              v-model="formData.name"
              type="text"
              placeholder="John Doe"
              class="input input-bordered w-full"
              required
            />
          </div>

          <!-- Email Input -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Email Address</span>
            </label>
            <input
              v-model="formData.email"
              type="email"
              placeholder="you@example.com"
              class="input input-bordered w-full"
              required
            />
          </div>

          <!-- Gender Select -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Gender</span>
            </label>
            <select v-model="formData.gender" class="select select-bordered w-full" required>
              <option v-for="option in genderOptions" :key="option.value" :value="option.value">
                {{ option.label }}
              </option>
            </select>
          </div>

          <!-- Birthday Input -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Date of Birth</span>
            </label>
            <input
              v-model="formData.birthday"
              type="date"
              class="input input-bordered w-full"
              required
            />
          </div>

          <!-- Bio Textarea -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Bio</span>
              <span class="label-text-alt text-base-content/50">Optional</span>
            </label>
            <textarea
              v-model="formData.bio"
              placeholder="Tell us about yourself..."
              class="textarea textarea-bordered w-full resize-none"
              rows="3"
            ></textarea>
            <label class="label">
              <span class="label-text-alt">{{ formData.bio.length }}/500 characters</span>
            </label>
          </div>

          <!-- Password Input -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Password</span>
            </label>
            <input
              v-model="formData.password"
              type="password"
              placeholder="Enter your password"
              class="input input-bordered w-full"
              required
            />
            <label class="label">
              <span
                class="label-text-alt"
                :class="isPasswordStrong() ? 'text-success' : 'text-warning'"
              >
                {{ isPasswordStrong() ? '✓ Strong password' : '⚠ Minimum 8 characters' }}
              </span>
            </label>
          </div>

          <!-- Password Confirmation Input -->
          <div class="form-control">
            <label class="label">
              <span class="label-text font-semibold">Confirm Password</span>
            </label>
            <input
              v-model="formData.passwordConfirmation"
              type="password"
              placeholder="Confirm your password"
              class="input input-bordered w-full"
              :class="
                formData.passwordConfirmation && !passwordsMatch()
                  ? 'input-error'
                  : formData.passwordConfirmation && passwordsMatch()
                    ? 'input-success'
                    : ''
              "
              required
            />
            <label v-if="formData.passwordConfirmation" class="label">
              <span
                class="label-text-alt"
                :class="passwordsMatch() ? 'text-success' : 'text-error'"
              >
                {{ passwordsMatch() ? '✓ Passwords match' : '✗ Passwords do not match' }}
              </span>
            </label>
          </div>

          <!-- Register Button -->
          <button
            type="submit"
            :disabled="isLoading || !isFormValid()"
            class="btn btn-primary btn-lg w-full mt-6"
          >
            <span v-if="isLoading" class="loading loading-spinner"></span>
            <span v-else>Create Account</span>
          </button>
        </form>

        <!-- Login Link -->
        <div class="text-center mt-6">
          <p class="text-base-content/70">
            Already have an account?
            <NuxtLink to="/login" class="link link-primary font-semibold">
              Sign in here
            </NuxtLink>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
