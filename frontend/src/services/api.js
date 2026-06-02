import axios from 'axios';

const api = axios.create({
  // URL oficial da API hospedada no Render
  baseURL: 'https://rota-certa-zihl.onrender.com/api', 
});

// O "Porteiro" do Vue: Antes de QUALQUER requisição sair pro Laravel, ele roda isso:
api.interceptors.request.use((config) => {
  // 1. Ele vai no disco do navegador e procura o Token
  const token = localStorage.getItem('token');
  
  // 2. Se o token existir, ele grampeia o token no cabeçalho da requisição
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  
  return config;
}, (error) => {
  return Promise.reject(error);
});

export default api;