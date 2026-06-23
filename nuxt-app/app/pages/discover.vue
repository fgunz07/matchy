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
  age: number
}

interface PaginatedResponse {
  data: User[]
  meta: {
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

const users = ref<User[]>([])
const isLoading = ref(true)
const noMore = ref<boolean>(false)
const isLoadingMore = ref(false)
const error = ref('')
const likedUserIds = ref<Set<number>>(new Set())
const currentPage = ref(1)
const lastPage = ref(1)

const { fetchUsers: fetchUsersApi, likeUser, unlikeUser } = useUsers()
const { fetchCurrentUser } = useAuth()

const toggleLike = async (userId: number) => {
  try {
    if (likedUserIds.value.has(userId)) {
      await unlikeUser(userId)
      likedUserIds.value.delete(userId)
    } else {
      await likeUser(userId)
      likedUserIds.value.add(userId)
    }
  } catch (err) {
    console.error('Error toggling like:', err)
  }
}

const isUserLiked = (userId: number): boolean => {
  return likedUserIds.value.has(userId)
}

const handleMessage = (userId: number) => {
  navigateTo(`/conversations/${userId}`)
}

const fetchUsers = async (page: number = 1) => {
  if (page === 1) {
    isLoading.value = true
  } else {
    isLoadingMore.value = true
  }
  error.value = ''

  try {
    const response = await fetchUsersApi(20, page)

    if (response.meta.last_page === page) {
      noMore.value = true
    }
    
    if (page === 1) {
      users.value = response.data
    } else {
      users.value.push(...response.data)
    }
    
    currentPage.value = response.meta?.current_page || page
    lastPage.value = response.meta?.last_page || 1
  } catch (err) {
    console.error('Error fetching users:', err)
    error.value = 'Failed to load users. Please try again.'
  } finally {
    isLoading.value = false
    isLoadingMore.value = false
  }
}

const loadMore = async () => {
  if (currentPage.value < lastPage.value && !isLoadingMore.value) {
    currentPage.value += 1
    await fetchUsers(currentPage.value)
  }
}

onMounted(() => {
  fetchUsers(1)
  fetchCurrentUser()
})
</script>

<template>
  <div class="p-0 h-screen flex flex-col">
    <div class="flex-1 overflow-y-auto">
      <!-- Header -->
      <div class="p-4 sticky top-0 bg-base-100 z-10">
        <h1 class="text-3xl font-bold mb-1">Discover</h1>
        <p class="text-base-content/70 text-sm">Find people to connect with</p>
      </div>

      <!-- Error State -->
      <div v-if="error" class="alert alert-error mx-4 mb-4">
        <span>{{ error }}</span>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center items-center h-96">
        <span class="loading loading-spinner loading-lg"></span>
      </div>

      <!-- Users List - Mobile Feed -->
      <div v-else-if="users.length > 0" class="space-y-3 px-4 pb-4">
        <NuxtLink
          v-for="user in users"
          :key="user.id"
          :to="`/users/${user.id}`"
          class="card bg-base-200 shadow-md hover:shadow-lg transition-shadow duration-200 cursor-pointer"
        >
          <div class="card-body p-4 flex flex-row gap-4 items-center">
            <!-- Avatar -->
            <div class="avatar shrink-0">
              <div class="w-16 h-16 rounded-full bg-base-300 flex items-center justify-center overflow-hidden">
                <UserAvatar :seed="user.name" />
              </div>
            </div>

            <!-- Card Content -->
            <div class="flex-1 flex flex-col justify-between min-w-0">
              <!-- Name & Info -->
              <div>
                <h3 class="font-bold text-lg truncate">{{ user.name }}</h3>
                <p class="text-sm text-base-content/70">
                  {{ user.age }}, {{ user.gender }}
                </p>
                <p class="text-sm text-base-content/60 flex items-center gap-1 mt-1">
                  <span>❤️</span>
                  <span>{{ user.likers_count ?? 0 }} likes</span>
                </p>
              </div>

              <!-- Action Buttons -->
              <div class="flex gap-2 mt-2">
                <button
                  @click.prevent.stop="toggleLike(user.id)"
                  class="btn btn-xs flex-1 transition-all duration-200"
                  :class="isUserLiked(user.id) ? 'btn-error' : 'btn-ghost'"
                >
                  <span class="text-base">{{ isUserLiked(user.id) ? '❤️' : '🤍' }}</span>
                </button>
                <button
                  @click.prevent.stop="handleMessage(user.id)"
                  class="btn btn-xs btn-primary flex-1"
                >
                  <span class="text-base">💬</span>
                </button>
              </div>
            </div>
          </div>
        </NuxtLink>

        <!-- Load More Button -->
        <div v-if="currentPage < lastPage" class="flex justify-center pt-4 pb-4">
          <button
            @click="loadMore"
            class="btn btn-primary btn-outline"
            :disabled="isLoadingMore"
          >
            <span v-if="isLoadingMore" class="loading loading-spinner loading-sm"></span>
            <span v-else>Load More</span>
          </button>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="flex items-center justify-center h-96">
        <p class="text-base-content/70 text-lg">No users found</p>
      </div>
    </div>
  </div>
</template>
