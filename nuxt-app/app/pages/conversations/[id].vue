<script setup lang="ts">
import { ref, onMounted, nextTick, onBeforeUnmount } from 'vue'

definePageMeta({
  layout: 'auth',
})

interface Message {
  id: number
  sender_id: number
  content: string
  created_at: string
  user: {
    id: number
    name: string
    avatar_url: string | null
  }
}

interface ConversationUser {
  id: number
  name: string
  avatar_url: string | null
}

const route = useRoute()
const router = useRouter()
const conversationUser = ref<ConversationUser | null>(null)
const messages = ref<Message[]>([])
const isLoading = ref(true)
const isSending = ref(false)
const isLoadingMore = ref(false)
const error = ref('')
const messageInput = ref('')
const messagesContainer = ref<HTMLElement>()
const currentUserId = ref<number | null>(null)
const conversationId = ref<number | null>(null)
const currentMessagePage = ref(1)
const hasMoreMessages = ref(true)

const { fetchMessages, sendMessage: sendMessageApi, createConversation, fetchConversation } = useConversations()
const { fetchUser } = useUsers()
const { getCurrentUser } = useAuth()

const formatTime = (dateString: string): string => {
  const date = new Date(dateString)
  return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
}

const fetchConversationData = async () => {
  isLoading.value = true
  error.value = ''
  currentMessagePage.value = 1
  hasMoreMessages.value = true

  try {
    const userId = parseInt(route.params.id as string)
    
    // Fetch user info
    const userData = await fetchUser(userId)
    conversationUser.value = userData.data || userData

    // Get current user from localStorage
    const currentUser = getCurrentUser()
    if (currentUser) {
      currentUserId.value = currentUser.id
    }

    // Create or get conversation with this user
    const conversationData = await createConversation(userId)
    const convo = conversationData.data || conversationData
    conversationId.value = convo.id

    // Fetch messages for this conversation (page 1)
    await loadMessages(convo.id, 1)
    
    // Scroll to bottom after loading
    await nextTick()
    scrollToBottom()
  } catch (err) {
    console.error('Error fetching conversation:', err)
    error.value = 'Failed to load conversation'
  } finally {
    isLoading.value = false
  }
}

const loadMessages = async (convoId: number, page: number) => {
  try {
    const messagesData = await fetchMessages(convoId, page)
    const newMessages = messagesData.data || messagesData || []
    
    if (page === 1) {
      messages.value = newMessages
    } else {
      // Prepend older messages and maintain scroll position
      const scrollHeight = messagesContainer.value?.scrollHeight || 0
      messages.value = [...newMessages, ...messages.value]
      
      // Restore scroll position after DOM update
      await nextTick()
      if (messagesContainer.value) {
        const newScrollHeight = messagesContainer.value.scrollHeight
        messagesContainer.value.scrollTop = newScrollHeight - scrollHeight
      }
    }
    
    currentMessagePage.value = page
    hasMoreMessages.value = newMessages.length > 0 && page === 1
  } catch (err) {
    console.error('Error loading messages:', err)
  }
}

const loadMoreMessages = async () => {
  if (isLoadingMore.value || !hasMoreMessages.value || !conversationId.value) return
  
  isLoadingMore.value = true
  try {
    await loadMessages(conversationId.value, currentMessagePage.value + 1)
  } finally {
    isLoadingMore.value = false
  }
}

const handleScroll = () => {
  if (!messagesContainer.value) return
  
  // Check if user scrolled near the top (within 200px)
  if (messagesContainer.value.scrollTop < 200 && hasMoreMessages.value && !isLoadingMore.value) {
    loadMoreMessages()
  }
}

const scrollToBottom = () => {
  if (messagesContainer.value) {
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight
  }
}

const sendMessage = async () => {
  if (!messageInput.value.trim() || !conversationId.value || !currentUserId.value) return

  isSending.value = true

  try {
    // Send message through API
    const response = await sendMessageApi(conversationId.value, messageInput.value)
    const newMessage = response.data || response

    // Add to local messages
    messages.value.push({
      id: newMessage.id,
      sender_id: currentUserId.value,
      content: newMessage.content,
      created_at: newMessage.created_at,
      user: {
        id: currentUserId.value,
        name: 'You',
        avatar_url: null,
      },
    })

    messageInput.value = ''

    await nextTick()
    scrollToBottom()
  } catch (err) {
    console.error('Error sending message:', err)
    error.value = 'Failed to send message'
  } finally {
    isSending.value = false
  }
}

const goBack = () => {
  router.back()
}

onMounted(() => {
  fetchConversationData()
  if (messagesContainer.value) {
    messagesContainer.value.addEventListener('scroll', handleScroll)
  }
})

onBeforeUnmount(() => {
  if (messagesContainer.value) {
    messagesContainer.value.removeEventListener('scroll', handleScroll)
  }
})
</script>

<template>
  <div class="p-0 h-auto flex flex-col">
    <!-- Header -->
    <div class="bg-base-200 shadow-md p-4 flex items-center gap-3">
      <button @click="goBack" class="btn btn-ghost btn-square btn-sm">
        <span class="text-xl">←</span>
      </button>
      <div v-if="conversationUser" class="avatar">
        <div class="w-10 h-10 rounded-full bg-base-300 flex items-center justify-center overflow-hidden">
          <UserAvatar :seed="conversationUser.name" />
        </div>
      </div>
      <h1 class="text-xl font-bold">{{ conversationUser?.name ?? 'Loading...' }}</h1>
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

    <!-- Messages -->
    <div v-else class="flex flex-col h-full bg-base-100">
      <!-- Messages Container -->
      <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-2 flex flex-col">
        <!-- Loading more messages indicator -->
        <div v-if="isLoadingMore" class="flex justify-center py-2">
          <span class="loading loading-spinner loading-sm"></span>
        </div>

        <div
          v-for="message in messages"
          :key="message.id"
          class="flex gap-2 w-full"
          :class="message.sender_id === currentUserId ? 'justify-end' : 'justify-start'"
        >
          <!-- Other user message (Left) -->
          <div v-if="message.sender_id !== currentUserId" class="max-w-xs">
            <div class="bg-base-200 rounded-2xl rounded-bl-sm px-4 py-2">
              <p class="text-sm text-base-content break-words">{{ message.content }}</p>
              <p class="text-xs text-base-content/60 mt-1">{{ formatTime(message.created_at) }}</p>
            </div>
          </div>

          <!-- My message (Right) -->
          <div v-else class="max-w-xs">
            <div class="bg-primary rounded-2xl rounded-br-sm px-4 py-2 text-primary-content">
              <p class="text-sm break-words">{{ message.content }}</p>
              <p class="text-xs opacity-70 mt-1 text-right">{{ formatTime(message.created_at) }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Message Input -->
      <div class="bg-base-100 border-t border-base-300 p-3">
        <form @submit.prevent="sendMessage" class="flex gap-2 items-end">
          <input
            v-model="messageInput"
            type="text"
            placeholder="Type a message..."
            class="input input-bordered flex-1 input-sm"
            @keyup.enter="sendMessage"
            :disabled="isSending"
          />
          <button type="submit" class="btn btn-primary btn-circle btn-sm" :disabled="!messageInput.trim() || isSending">
            <span v-if="isSending" class="loading loading-spinner loading-sm"></span>
            <span v-else class="text-lg">▶</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>
