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
        
        <form @submit.prevent="executarLogin" class="space-y-4">
          
          <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-1.5 ml-1">Seu E-mail</label>
            <input 
              type="email" 
              v-model="emailDoUsuario" 
              placeholder="email@exemplo.com" 
              class="w-full text-[#1A1A1A] bg-transparent border-2 border-gray-200 rounded-2xl py-3 pl-4 pr-4 focus:outline-none focus:border-[#1A1A1A] transition-all font-medium"
            >
            <p v-if="emailDoUsuario && !emailEhValido" class="text-red-500 text-xs font-bold mt-1 ml-1">E-mail com formato inválido</p>
          </div>
          
          <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-1.5 ml-1">Sua Senha</label>
            <div class="relative w-full">
              <input 
                :type="mostrarSenhaDoUsuario ? 'text' : 'password'" 
                v-model="senhaDoUsuario" 
                placeholder="••••••••" 
                class="w-full text-[#1A1A1A] bg-transparent border-2 border-gray-200 rounded-2xl py-3 pl-4 pr-12 focus:outline-none focus:border-[#1A1A1A] transition-all font-medium"
              >
              
              <button 
                type="button" 
                @click="mostrarSenhaDoUsuario = !mostrarSenhaDoUsuario" 
                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#1A1A1A] transition-colors"
              >
                <svg v-if="mostrarSenhaDoUsuario" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 01-1.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                </svg>
              </button>
            </div>

            <div class="text-right mt-2">
              <router-link to="/esqueci-senha" class="text-sm text-[#2D4483] hover:text-black font-bold transition-colors">
                Esqueceu a senha?
              </router-link>
            </div>
          </div>

          <!-- Termos de Política e Privacidade -->
          <div class="flex items-start gap-3 my-4">
            <input 
              type="checkbox" 
              id="checkboxAceitouOsTermosDePrivacidade" 
              v-model="aceitouOsTermosDePrivacidade"
              class="w-5 h-5 rounded border-gray-300 text-[#1A1A1A] focus:ring-[#1A1A1A] mt-0.5 cursor-pointer"
            >
            <label for="checkboxAceitouOsTermosDePrivacidade" class="text-xs text-[#1A1A1A]/70 leading-normal cursor-pointer select-none">
              Li e concordo com os <span class="font-bold text-[#1A1A1A]">Termos de Política e Privacidade</span> do RotaCerta, compreendendo que meus dados de localização em tempo real serão utilizados unicamente para a roteirização e otimização das entregas.
            </label>
          </div>

          <button 
            type="submit" 
            :disabled="!formularioEstaValido"
            :class="[!formularioEstaValido ? 'opacity-50 cursor-not-allowed' : 'hover:bg-black']"
            class="w-full py-4 bg-[#1A1A1A] text-white font-black text-lg rounded-2xl transition-all shadow-xl uppercase tracking-widest mt-2"
          >
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
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api' 
import { validarFormatoDeEmail } from '../utils/validadoresDeFormatacao.js'
import { exibirNotificacao } from '../utils/sistemaDeNotificacoes.js'

import rotaLogo from '../assets/rotaLogo.png'

const mostrarSenhaDoUsuario = ref(false)
const aceitouOsTermosDePrivacidade = ref(false)

const roteador = useRouter()
const emailDoUsuario = ref('')
const senhaDoUsuario = ref('')

const emailEhValido = computed(() => validarFormatoDeEmail(emailDoUsuario.value))

const formularioEstaValido = computed(() => {
  return emailDoUsuario.value && senhaDoUsuario.value && emailEhValido.value && aceitouOsTermosDePrivacidade.value
})

const executarLogin = async () => {
  if (!formularioEstaValido.value) {
    exibirNotificacao("Por favor, preencha o e-mail e a senha corretamente, e concorde com os Termos de Política e Privacidade!", "aviso");
    return;
  }

  try {
    const respostaDoLogin = await api.post('/login', {
      email: emailDoUsuario.value,
      password: senhaDoUsuario.value
    })

    const tokenDeAutenticacao = respostaDoLogin.data.token
    
    if (tokenDeAutenticacao) {
      localStorage.setItem('token', tokenDeAutenticacao)
      
      const respostaDoUsuario = await api.get('/me')
      const dadosDoUsuario = respostaDoUsuario.data
      
      localStorage.setItem('tipoUsuario', dadosDoUsuario.tipo)
      
      if (dadosDoUsuario.tipo === 'lojista') {
        roteador.push('/dashboard-lojista')
      } else if (dadosDoUsuario.tipo === 'entregador') {
        roteador.push('/painel-entregador') 
      } else {
        roteador.push('/home')
      }
    }

  } catch (erroOcorrido) {
    console.error("Erro do servidor:", erroOcorrido)
    
    if (erroOcorrido.response && erroOcorrido.response.status === 401) {
      exibirNotificacao("E-mail ou senha incorretos.", "erro");
    } else {
      exibirNotificacao("Erro ao conectar. O 'php artisan serve' está rodando no outro terminal?", "erro");
    }
  }
}
</script>