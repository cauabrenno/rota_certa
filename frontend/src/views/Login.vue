<template>
  <div class="min-h-screen bg-gradient-to-br from-[#C2F2D9] to-[#91d1b0] flex items-center justify-center p-4">
    
    <div class="bg-white/70 backdrop-blur-2xl border border-white/50 rounded-[3rem] shadow-2xl flex max-w-5xl w-full overflow-hidden">
      
      <div class="hidden md:flex w-1/2 bg-[#1A1A1A] items-center justify-center relative overflow-hidden">
        <div class="absolute inset-0 bg-black/20 z-10"></div>
        <img 
          src="../assets/entregador.jpg" 
          alt="Entregador RotaCerta" 
          class="absolute inset-0 w-full h-full object-cover transition-transform duration-[3000ms] hover:scale-110"
        > 
      </div>

      <div class="w-full md:w-1/2 p-6 lg:p-10 flex flex-col justify-center bg-white/40">
        
        <div class="flex justify-center w-full mb-4">
          <img :src="rotaLogo" alt="Rota Certa" class="h-40 lg:h-52 object-contain drop-shadow-sm">
        </div>

        <div class="mb-6 text-center md:text-left">
          <h2 class="text-3xl lg:text-4xl font-black text-[#554a4a] tracking-tighter mb-1">Bem-vindo!</h2>
          <p class="text-[#1A1A1A]/60 font-medium italic text-sm lg:text-base">Acesse sua conta no RotaCerta</p>
        </div>
        
        <form @submit.prevent="handleLogin" class="space-y-4">
          
          <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-1.5 ml-1">Seu E-mail</label>
            <input 
              type="email" 
              v-model="email" 
              placeholder="email@exemplo.com" 
              class="w-full text-[#1A1A1A] bg-transparent border-2 border-gray-200 rounded-2xl py-3 pl-4 pr-4 focus:outline-none focus:border-[#1A1A1A] transition-all font-medium"
            >
          </div>
          
          <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-1.5 ml-1">Sua Senha</label>
            <div class="relative w-full">
              <input 
                :type="mostrarSenha ? 'text' : 'password'" 
                v-model="senha" 
                placeholder="••••••••" 
                class="w-full text-[#1A1A1A] bg-transparent border-2 border-gray-200 rounded-2xl py-3 pl-4 pr-12 focus:outline-none focus:border-[#1A1A1A] transition-all font-medium"
              >
              
              <button 
                type="button" 
                @click="mostrarSenha = !mostrarSenha" 
                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#1A1A1A] transition-colors"
              >
                <svg v-if="mostrarSenha" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                </svg>
              </button>
            </div>

            <div class="text-right mt-2">
              <router-link to="/esqueci-senha" class="text-sm text-[#2D4483] hover:text-black font-bold transition-colors">
                Esqueceu a senha?
              </router-link>
            </div>
          </div>

          <button type="submit" class="w-full py-4 bg-[#1A1A1A] text-white font-black text-lg rounded-2xl hover:bg-black transition-all shadow-xl uppercase tracking-widest mt-2">
            Entrar no Sistema
          </button>

          <div class="text-center mt-4">
            <p class="text-[#1A1A1A]/60 font-medium text-sm mb-2">Ainda não tem acesso?</p>
            
            <router-link to="/selecao">
              <button type="button" class="w-full md:w-auto px-10 py-2.5 border-2 border-[#1A1A1A] text-[#1A1A1A] font-black rounded-2xl hover:bg-[#1A1A1A] hover:text-white transition-all duration-300 shadow-md">
                Criar Nova Conta
              </button>
            </router-link>
          </div>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api' 

import rotaLogo from '../assets/rotaLogo.png'

const mostrarSenha = ref(false)

const router = useRouter()
const email = ref('')
const senha = ref('')

const handleLogin = async () => {
  if (!email.value || !senha.value) {
    alert("Por favor, preencha tudo!");
    return;
  }

  try {
    // 1. Faz o login e pega o Token
    const response = await api.post('/login', {
      email: email.value,
      password: senha.value
    })

    const token = response.data.token
    
    if (token) {
      // Salva o token para as próximas requisições
      localStorage.setItem('token', token)
      
      // 2. Busca os dados do usuário para descobrir quem ele é
      const userRes = await api.get('/me')
      const usuario = userRes.data
      
      // 3. Redirecionamento Inteligente baseado no 'tipo'
      if (usuario.tipo === 'lojista') {
        router.push('/dashboard-lojista')
      } else if (usuario.tipo === 'entregador') {
        router.push('/dashboard-entregador') 
      } else {
        router.push('/home') // Se for cliente ou vazio, vai pra home
      }
    }

  } catch (error) {
    console.error("Erro do servidor:", error)
    
    if (error.response && error.response.status === 401) {
      alert("E-mail ou senha incorretos.");
    } else {
      alert("Erro ao conectar. O 'php artisan serve' está rodando no outro terminal?");
    }
  }
}
</script>