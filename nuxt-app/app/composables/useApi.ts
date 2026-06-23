export const useApi = () => {
  const API_BASE_URL = 'http://localhost/api'

  const getHeaders = () => {
    const token = localStorage.getItem('auth_token')
    return {
      'Content-Type': 'application/json',
      ...(token && { 'Authorization': `Bearer ${token}` })
    }
  }

  const request = async (method: string, endpoint: string, data?: any) => {
    try {
      const url = `${API_BASE_URL}${endpoint}`
      const options: RequestInit = {
        method,
        headers: getHeaders(),
      }

      if (data) {
        options.body = JSON.stringify(data)
      }

      const response = await fetch(url, options)

      if (!response.ok) {
        throw new Error(`API Error: ${response.status}`)
      }

      return await response.json()
    } catch (error) {
      console.error('API Error:', error)
      throw error
    }
  }

  return {
    get: (endpoint: string) => request('GET', endpoint),
    post: (endpoint: string, data?: any) => request('POST', endpoint, data),
    patch: (endpoint: string, data: any) => request('PATCH', endpoint, data),
    delete: (endpoint: string) => request('DELETE', endpoint),
  }
}
