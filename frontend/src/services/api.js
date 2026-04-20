import axios from 'axios'

const api = axios.create({
  // URL padrão do Laravel rodando localmente. 
  // Se o back-end configurou diferente, depois ajustamos!
  baseURL: 'http://localhost:8000/api', 
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

export default api