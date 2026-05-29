<template>
  <div class="min-h-screen bg-gradient-to-br from-[#C2F2D9] to-[#91d1b0] flex items-center justify-center p-4">
    <div class="bg-white/70 backdrop-blur-2xl border border-white/50 rounded-[3rem] shadow-2xl p-10 lg:p-14 max-w-lg w-full">
      
      <div class="mb-8 text-center">
        <img :src="iRota" class="h-24 mx-auto mb-6 object-contain drop-shadow-sm">
        <h2 class="text-3xl font-black italic tracking-tighter text-[#1A1A1A] mb-1">Nova Senha</h2>
        <p class="text-gray-500 font-medium text-sm">Crie uma senha forte e segura.</p>
      </div>

      <form @submit.prevent="lidarComRedefinicao" class="space-y-5">
        <div>
          <label class="block text-sm font-bold text-[#1A1A1A] mb-1.5 ml-1">Nova Senha</label>
          <div class="relative w-full">
            <input 
              :type="mostrarSenha ? 'text' : 'password'" 
              v-model="password" 
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
          <p v-if="password && !tamanhoMinimoEhValido" class="text-red-500 text-xs font-bold mt-1 ml-1">A senha precisa ter pelo menos 8 caracteres.</p>
        </div>

        <div>
          <label class="block text-sm font-bold text-[#1A1A1A] mb-1.5 ml-1">Confirmar Senha</label>
          <div class="relative w-full">
            <input 
              :type="mostrarConfirmarSenha ? 'text' : 'password'" 
              v-model="password_confirmation" 
              placeholder="••••••••" 
              class="w-full text-[#1A1A1A] bg-transparent border-2 border-gray-200 rounded-2xl py-3 pl-4 pr-12 focus:outline-none focus:border-[#1A1A1A] transition-all font-medium"
            >
            <button 
              type="button" 
              @click="mostrarConfirmarSenha = !mostrarConfirmarSenha" 
              class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-[#1A1A1A] transition-colors"
            >
              <svg v-if="mostrarConfirmarSenha" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
              </svg>
            </button>
          </div>
          <p v-if="password_confirmation && !senhasCoincidem" class="text-red-500 text-xs font-bold mt-1 ml-1">As senhas não coincidem.</p>
        </div>

        <button 
          type="submit" 
          :disabled="!formularioEstaValido" 
          :class="[!formularioEstaValido ? 'opacity-50 cursor-not-allowed' : 'hover:bg-black']"
          class="w-full py-5 bg-[#1A1A1A] text-white font-black text-lg rounded-2xl transition-all shadow-xl uppercase tracking-widest mt-2 flex items-center justify-center gap-2"
        >
          <span v-if="carregando" class="animate-spin text-xl">⏳</span>
          {{ carregando ? 'Salvando...' : 'Redefinir Senha' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import iRota from '../assets/iRota.png'
import api from '../services/api'
import { exibirNotificacao } from '../utils/sistemaDeNotificacoes.js'

const route = useRoute()
const router = useRouter()

const password = ref('')
const password_confirmation = ref('')
const mostrarSenha = ref(false)
const mostrarConfirmarSenha = ref(false)
const carregando = ref(false)

const senhasCoincidem = computed(() => password.value === password_confirmation.value)
const tamanhoMinimoEhValido = computed(() => password.value.length >= 8)

const formularioEstaValido = computed(() => {
  return password.value && password_confirmation.value && senhasCoincidem.value && tamanhoMinimoEhValido.value && !carregando.value
})

const lidarComRedefinicao = async () => {
  if (!formularioEstaValido.value) {
    exibirNotificacao("Por favor, preencha os campos de senha corretamente!", "aviso");
    return;
  }

  carregando.value = true
  try {
    await api.post('/resetar-senha', {
      token: route.query.token,
      email: route.query.email,
      password: password.value,
      password_confirmation: password_confirmation.value
    })
    exibirNotificacao("Senha alterada com sucesso! Você já pode logar.", "sucesso")
    router.push('/login')
  } catch (erroOcorrido) {
    console.error(erroOcorrido)
    exibirNotificacao("Erro! O link expirou, é inválido ou esse e-mail não existe no sistema.", "erro")
  } finally {
    carregando.value = false
  }
}
</script>