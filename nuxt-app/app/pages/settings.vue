<script setup lang="ts">
import { ref, onMounted } from 'vue'

definePageMeta({
  layout: 'auth',
})

interface CurrentUser {
  id: number
  name: string
  email: string
  avatar_url: string | null
  gender: string
  birthday: string
  bio: string | null
}

const router = useRouter()
const user = ref<CurrentUser | null>(null)
const isLoading = ref(false)
const isSendingPasswordReset = ref(false)
const error = ref('')
const success = ref('')

// Form fields
const name = ref('')
const email = ref('')
const bio = ref('')
const gender = ref('')
const birthday = ref('')
const age = ref<number | null>(null)

const { getCurrentUser, updateCurrentUser, forgetPassword } = useAuth()

const calculateAge = (birthdayStr: string): number => {
  const birthDate = new Date(birthdayStr)
  const today = new Date()
  let calculatedAge = today.getFullYear() - birthDate.getFullYear()
  const monthDiff = today.getMonth() - birthDate.getMonth()
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    calculatedAge--
  }
  return calculatedAge
}

const loadUserData = () => {
  const currentUser = getCurrentUser()
  if (currentUser) {
    user.value = currentUser
    name.value = currentUser.name || ''
    email.value = currentUser.email || ''
    bio.value = currentUser.bio || ''
    gender.value = currentUser.gender || ''
    birthday.value = currentUser.birthday || ''
    if (currentUser.birthday) {
      age.value = calculateAge(currentUser.birthday)
    }
  }
}

const handleUpdateProfile = async () => {
  if (!user.value) return

  isLoading.value = true
  error.value = ''
  success.value = ''

  try {
    const updateData: any = {
      name: name.value,
      email: email.value,
      bio: bio.value,
      gender: gender.value,
      birthday: birthday.value,
    }

    await updateCurrentUser(user.value.id, updateData)
    
    // Update age after birthday change
    if (birthday.value) {
      age.value = calculateAge(birthday.value)
    }
    
    success.value = 'Profile updated successfully!'

    // Clear success message after 3 seconds
    setTimeout(() => {
      success.value = ''
    }, 3000)
  } catch (err: any) {
    console.error('Update error:', err)
    error.value = err.message || 'Failed to update profile'
  } finally {
    isLoading.value = false
  }
}

const handleForgotPassword = async () => {
  if (!user.value?.email) return

  isSendingPasswordReset.value = true
  error.value = ''
  success.value = ''

  try {
    await forgetPassword(user.value.email)
    success.value = 'Password reset link sent to your email!'
    setTimeout(() => {
      success.value = ''
    }, 3000)
  } catch (err: any) {
    console.error('Forgot password error:', err)
    error.value = err.message || 'Failed to send password reset link'
  } finally {
    isSendingPasswordReset.value = false
  }
}

const goBack = () => {
  router.back()
}

onMounted(() => {
  loadUserData()
})
</script>

<template>
  <div class="p-4 max-w-2xl mx-auto">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6">
      <button @click="goBack" class="btn btn-ghost btn-square btn-sm">
        <span class="text-xl">←</span>
      </button>
      <h1 class="text-3xl font-bold">Settings</h1>
    </div>

    <!-- Success Message -->
    <div v-if="success" class="alert alert-success mb-4">
      <span>{{ success }}</span>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="alert alert-error mb-4">
      <span>{{ error }}</span>
    </div>

    <!-- Settings Form -->
    <div class="card bg-base-200 shadow-md">
      <div class="card-body">
        <h2 class="card-title mb-4">Edit Profile</h2>

        <!-- Name -->
        <div class="form-control w-full mb-4">
          <label class="label">
            <span class="label-text font-semibold">Name</span>
          </label>
          <input
            v-model="name"
            type="text"
            placeholder="Your name"
            class="input input-bordered w-full"
            :disabled="isLoading"
          />
        </div>

        <!-- Email -->
        <div class="form-control w-full mb-4">
          <label class="label">
            <span class="label-text font-semibold">Email</span>
          </label>
          <input
            v-model="email"
            type="email"
            placeholder="Your email"
            class="input input-bordered w-full"
            :disabled="isLoading"
          />
        </div>

        <!-- Bio -->
        <div class="form-control w-full mb-4">
          <label class="label">
            <span class="label-text font-semibold">Bio</span>
          </label>
          <textarea
            v-model="bio"
            placeholder="Tell us about yourself"
            class="textarea textarea-bordered w-full h-24"
            :disabled="isLoading"
          ></textarea>
          <label class="label">
            <span class="label-text-alt">{{ bio.length }}/500 characters</span>
          </label>
        </div>

        <!-- Gender -->
        <div class="form-control w-full mb-4">
          <label class="label">
            <span class="label-text font-semibold">Gender</span>
          </label>
          <select v-model="gender" class="select select-bordered w-full" :disabled="isLoading">
            <option value="">Select gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>

        <!-- Birthday -->
        <div class="form-control w-full mb-4">
          <label class="label">
            <span class="label-text font-semibold">Birthday</span>
          </label>
          <input
            v-model="birthday"
            type="date"
            class="input input-bordered w-full"
            :disabled="isLoading"
          />
          <label v-if="age" class="label">
            <span class="label-text-alt">Age: {{ age }} years old</span>
          </label>
        </div>

        <!-- Divider -->
        <div class="divider my-4">Security</div>

        <!-- Forgot Password -->
        <div class="mb-6">
          <button
            @click="handleForgotPassword"
            class="btn btn-outline btn-warning w-full"
            :disabled="isSendingPasswordReset"
          >
            <span v-if="isSendingPasswordReset" class="loading loading-spinner loading-sm"></span>
            <span v-else>Send Password Reset Link</span>
          </button>
          <p class="text-xs text-base-content/60 mt-2">
            We'll send a password reset link to your email.
          </p>
        </div>

        <!-- Submit Button -->
        <div class="card-actions justify-end">
          <button
            @click="handleUpdateProfile"
            class="btn btn-primary"
            :disabled="isLoading"
          >
            <span v-if="isLoading" class="loading loading-spinner loading-sm"></span>
            <span v-else>Save Changes</span>
          </button>
        </div>
      </div>
    </div>

    <!-- User Info Card -->
    <div v-if="user" class="card bg-base-200 shadow-md mt-6">
      <div class="card-body">
        <h2 class="card-title mb-4">Account Info</h2>
        <div class="space-y-2">
          <p class="text-sm">
            <span class="font-semibold">User ID:&nbsp;</span>
            <span class="text-base-content/70">#{{ user.id }}</span>
          </p>
          <p class="text-sm">
            <span class="font-semibold">Gender:&nbsp;</span>
            <span class="text-base-content/70">{{ user.gender }}</span>
          </p>
          <p class="text-sm">
            <span class="font-semibold">Birthday:&nbsp;</span>
            <span class="text-base-content/70">{{ user.birthday }}</span>
          </p>
          <p class="text-sm">
            <span class="font-semibold">Member Since:&nbsp;</span>
            <span class="text-base-content/70">2026-06-23</span>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>
