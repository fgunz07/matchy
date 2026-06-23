<script setup lang="ts">
import { ref, onMounted } from 'vue'

definePageMeta({
  layout: 'auth',
})

interface User {
  id: number
  slug: string
  name: string
  avatar_url: string | null
  gender: string
  birthday: string
  bio: string | null
  likers_count?: number
  age?: number
}

const route = useRoute()
const router = useRouter()
const user = ref<User | null>(null)
const isLoading = ref(true)
const error = ref('')
const isLiked = ref(false)

const { fetchUser, likeUser, unlikeUser } = useUsers()
const { getCurrentUser } = useAuth()

const handleLoadUser = async () => {
  isLoading.value = true
  error.value = ''

  try {
    const userId = parseInt(route.params.id as string)
    const userData = await fetchUser(userId)
    user.value = userData.data || userData
    
    // Check if current user has liked this user
    const currentUser = getCurrentUser()
    if (currentUser) {
      const likedUsers = JSON.parse(localStorage.getItem(`liked_users_${currentUser.id}`) || '[]')
      isLiked.value = likedUsers.includes(userId)
    }
  } catch (err) {
    console.error('Error fetching user:', err)
    error.value = 'User not found'
  } finally {
    isLoading.value = false
  }
}

const handleLike = async () => {
  try {
    if (user.value) {
      const currentUser = getCurrentUser()
      if (!currentUser) return
      
      if (isLiked.value) {
        // Unlike
        await unlikeUser(user.value.id)
        isLiked.value = false
        
        // Update localStorage
        const likedUsers = JSON.parse(localStorage.getItem(`liked_users_${currentUser.id}`) || '[]')
        const updatedLikedUsers = likedUsers.filter((id: number) => id !== user.value.id)
        localStorage.setItem(`liked_users_${currentUser.id}`, JSON.stringify(updatedLikedUsers))
      } else {
        // Like
        await likeUser(user.value.id)
        isLiked.value = true
        
        // Update localStorage
        const likedUsers = JSON.parse(localStorage.getItem(`liked_users_${currentUser.id}`) || '[]')
        if (!likedUsers.includes(user.value.id)) {
          likedUsers.push(user.value.id)
        }
        localStorage.setItem(`liked_users_${currentUser.id}`, JSON.stringify(likedUsers))
      }
      
      // Reload user to update like count
      await handleLoadUser()
    }
  } catch (err) {
    console.error('Error toggling like:', err)
  }
}

const goBack = () => {
  router.back()
}

onMounted(() => {
  handleLoadUser()
})
</script>

<template>
  <div class="p-0 h-screen flex flex-col">
    <!-- Header with Back Button -->
    <div class="bg-base-200 shadow-md p-4 flex items-center gap-4">
      <button @click="goBack" class="btn btn-ghost btn-square btn-sm">
        <span class="text-xl">←</span>
      </button>
      <h1 class="text-2xl font-bold">Profile</h1>
    </div>

    <!-- Loading State -->
    <div v-if="isLoading" class="flex-1 flex justify-center items-center">
      <span class="loading loading-spinner loading-lg"></span>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="flex-1 flex flex-col items-center justify-center">
      <p class="text-error text-lg mb-4">{{ error }}</p>
      <button @click="goBack" class="btn btn-primary">Go Back</button>
    </div>

    <!-- Profile Content -->
    <div v-else-if="user" class="flex-1 overflow-y-auto">
      <div class="p-4 space-y-4">
        <!-- Avatar Section -->
        <div class="flex justify-center">
          <div class="avatar">
            <div class="w-32 h-32 rounded-full bg-base-300 flex items-center justify-center overflow-hidden shadow-lg">
              <UserAvatar :seed="user.name" />
            </div>
          </div>
        </div>

        <!-- User Info Card -->
        <div class="card bg-base-200 shadow-md">
          <div class="card-body">
            <!-- Name -->
            <h2 class="card-title text-3xl font-bold">{{ user.name }}</h2>

            <!-- Age & Gender -->
            <div class="text-lg text-base-content/80">
              <span class="badge badge-lg badge-primary mr-2">
                {{ user.age }} years old
              </span>
              <span class="badge badge-lg">{{ user.gender }}</span>
            </div>

            <!-- Likes Count -->
            <div class="divider my-2"></div>
            <div class="flex items-center gap-2">
              <span class="text-2xl">❤️</span>
              <span class="text-lg font-semibold">{{ user.likers_count ?? 0 }} likes</span>
            </div>
          </div>
        </div>

        <!-- Bio Section -->
        <div class="card bg-base-200 shadow-md">
          <div class="card-body">
            <h3 class="card-title text-lg">About</h3>
            <p class="text-base text-base-content/80 leading-relaxed">
              {{ user.bio || 'No bio available' }}
            </p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
          <NuxtLink
            :to="`/conversations/${user.id}`"
            class="btn btn-primary flex-1"
          >
            <span class="text-lg">💬</span>
            Message
          </NuxtLink>
          <button
            @click="handleLike"
            class="btn flex-1 transition-all duration-200"
            :class="isLiked ? 'btn-error' : 'btn-ghost'"
          >
            <span class="text-lg">{{ isLiked ? '❤️' : '🤍' }}</span>
            {{ isLiked ? 'Unlike' : 'Like' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="flex-1 flex items-center justify-center">
      <p class="text-base-content/70 text-lg">No user data</p>
    </div>
  </div>
</template>
