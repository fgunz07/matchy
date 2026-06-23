export const useUsers = () => {
  const api = useApi()

  const fetchUsers = async (perPage: number = 15, page: number = 1) => {
    return api.get(`/users?per_page=${perPage}&page=${page}`)
  }

  const fetchUser = async (userId: number) => {
    return api.get(`/users/${userId}`)
  }

  const likeUser = async (userId: number) => {
    return api.post(`/users/${userId}/like`, {})
  }

  const unlikeUser = async (userId: number) => {
    return api.post(`/users/${userId}/unlike`, {})
  }

  return {
    fetchUsers,
    fetchUser,
    likeUser,
    unlikeUser,
  }
}
