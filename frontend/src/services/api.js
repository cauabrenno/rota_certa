import axios from 'axios'

const api = axios.create({
baseURL: 'http://172.20.10.8:8000/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

// Aqui entra a mágica: O Interceptor
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token') // Pega o crachá no cofre
  
  if (token) {
    // Se tiver crachá, anexa na requisição para o Laravel liberar a porta
    config.headers.Authorization = `Bearer ${token}`
  }
  
  return config
})

export default api