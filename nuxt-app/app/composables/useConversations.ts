export const useConversations = () => {
  const api = useApi()

  const fetchConversations = async (userId: number) => {
    return api.get(`/users/${userId}/conversations?include=receiver,lastMessage`)
  }

  const fetchUserConversations = async (userId: number) => {
    return api.get(`/users/${userId}/conversations`)
  }

  const createConversation = async (userId: number) => {
    return api.post(`/conversations`, { receiver_id: userId })
  }

  const fetchConversation = async (conversationId: number) => {
    return api.get(`/conversations/${conversationId}`)
  }

  const deleteConversation = async (conversationId: number) => {
    return api.delete(`/conversations/${conversationId}`)
  }

  const fetchMessages = async (conversationId: number, page: number = 1) => {
    return api.get(`/conversations/${conversationId}/messages?page=${page}&per_page=20`)
  }

  const sendMessage = async (conversationId: number, content: string) => {
    return api.post(`/conversations/${conversationId}/messages`, { content })
  }

  return {
    fetchConversations,
    fetchUserConversations,
    createConversation,
    fetchConversation,
    deleteConversation,
    fetchMessages,
    sendMessage,
  }
}
