import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Aqui entra a mágica: O Interceptor
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token') // Pega o crachá
  
  if (token) {
    // Se tiver crachá, anexa na requisição para o Laravel liberar a porta
    config.headers.Authorization = `Bearer ${token}`
  }
  
  return config
})

export default api