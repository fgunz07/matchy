<script setup lang="ts">
import { ref, onMounted } from 'vue'

definePageMeta({
  layout: 'auth',
})

interface Conversation {
  id: number
  sender_id: number
  receiver_id: number
  receiver: {
    id: number
    name: string
    avatar_url: string
  }
  last_message: {
    id: number
    content: string
  } | null
  last_message_at: null
  is_unread: boolean
}

const conversations = ref<Conversation[]>([])
const isLoading = ref(true)
const error = ref('')

const { fetchConversations } = useConversations()
const { getCurrentUser } = useAuth()
const user = getCurrentUser()

const formatTime = (dateString: string): string => {
  const date = new Date(dateString)
  const today = new Date()
  const yesterday = new Date(today)
  yesterday.setDate(yesterday.getDate() - 1)

  if (date.toDateString() === today.toDateString()) {
    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
  } else if (date.toDateString() === yesterday.toDateString()) {
    return 'Yesterday'
  } else {
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
  }
}

const handleFetchConversations = async () => {
  isLoading.value = true
  error.value = ''

  try {
    if (!user) {
      return
    }
    const response = await fetchConversations(user.id)
    conversations.value = response.data || response
  } catch (err) {
    console.error('Error fetching conversations:', err)
    error.value = 'Failed to load conversations. Please try again.'
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  handleFetchConversations()
})
</script>

<template>
  <div class="p-0 h-screen flex flex-col">
    <div class="flex-1 overflow-y-auto">
      <!-- Header -->
      <div class="p-4 sticky top-0 bg-base-100 z-10">
        <h1 class="text-3xl font-bold mb-1">Messages</h1>
        <p class="text-base-content/70 text-sm">Conversations with people you match with</p>
      </div>

      <!-- Error State -->
      <div v-if="error" class="alert alert-error mx-4 mb-4">
        <span>{{ error }}</span>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="flex justify-center items-center h-96">
        <span class="loading loading-spinner loading-lg"></span>
      </div>

      <!-- Conversations List -->
      <div v-else-if="conversations.length > 0" class="space-y-2 px-4 pb-4">
        <NuxtLink
          v-for="conversation in conversations"
          :key="conversation.id"
          :to="`/conversations/${conversation.receiver_id}`"
          class="card bg-base-200 shadow-sm hover:shadow-md transition-shadow duration-200 cursor-pointer"
        >
          <div class="card-body p-4 flex flex-row gap-3 items-start">
            <!-- Avatar -->
            <div class="avatar shrink-0 pt-1">
              <div class="w-14 h-14 rounded-full bg-base-300 flex items-center justify-center overflow-hidden">
                <UserAvatar :seed="conversation.receiver.name" />
              </div>
            </div>

            <!-- Conversation Content -->
            <div class="flex-1 flex flex-col justify-between min-w-0 gap-1">
              <!-- Name & Time -->
              <div class="flex justify-between items-start gap-2">
                <h3 class="font-bold text-base truncate" :class="{ 'text-primary font-bold': conversation.is_unread }">
                  {{ conversation.receiver.name }}
                </h3>
                <span class="text-xs text-base-content/70 shrink-0">
                  {{ formatTime(conversation.last_message_at || '') }}
                </span>
              </div>

              <!-- Last Message -->
              <p class="text-sm text-base-content/70 truncate line-clamp-1" :class="{ 'text-primary font-semibold': conversation.is_unread }">
                {{ conversation?.last_message?.content || '' }}
              </p>
            </div>

            <!-- Unread Badge -->
            <div v-if="conversation.is_unread" class="badge badge-primary badge-sm shrink-0 mt-1"></div>
          </div>
        </NuxtLink>
      </div>

      <!-- Empty State -->
      <div v-else class="flex flex-col items-center justify-center h-96">
        <div class="text-6xl mb-4">💬</div>
        <p class="text-base-content/70 text-lg">No conversations yet</p>
        <p class="text-base-content/60 text-sm">Start by liking someone to begin a conversation</p>
      </div>
    </div>
  </div>
</template>
