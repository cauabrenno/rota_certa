<template>
  <div class="min-h-screen bg-gradient-to-br from-[#C2F2D9] to-[#91d1b0] flex items-center justify-center p-4">
    
    <div class="bg-white/70 backdrop-blur-2xl border border-white/50 rounded-[3rem] shadow-2xl p-10 lg:p-16 max-w-lg w-full">
      
      <div v-if="!linkEnviado">
        <div class="mb-10 text-center">
          <div class="flex justify-center mb-6">
            <img :src="iRota" alt="Rota Certa" class="h-24 object-contain">
          </div>
          <h2 class="text-3xl font-black text-[#1A1A1A] tracking-tighter mb-2 italic">Recuperar Senha</h2>
          <p class="text-[#1A1A1A]/60 font-medium">Enviaremos um link de recuperação para o seu e-mail.</p>
        </div>

        <form @submit.prevent="solicitarLink" class="space-y-6">
          <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-2 ml-1">Seu E-mail</label>
            <input 
              type="email" 
              v-model="email" 
              placeholder="seu-email@exemplo.com" 
              required
              class="w-full text-[#1A1A1A] bg-transparent border-2 border-gray-200 rounded-2xl py-4 px-4 focus:outline-none focus:border-[#1A1A1A] transition-all font-medium"
            >
            <p v-if="email && !emailEhValido" class="text-red-500 text-xs font-bold mt-1 ml-1">E-mail com formato inválido</p>
          </div>

          <button 
            type="submit" 
            :disabled="!formularioEstaValido" 
            :class="[!formularioEstaValido ? 'opacity-50 cursor-not-allowed' : 'hover:bg-black']"
            class="w-full py-5 bg-[#1A1A1A] text-white font-black text-lg rounded-2xl transition-all shadow-xl uppercase tracking-widest flex items-center justify-center gap-3"
          >
            <span v-if="loading" class="animate-spin text-xl">⏳</span>
            {{ loading ? 'Enviando...' : 'Enviar Link' }}
          </button>
        </form>
      </div>

      <div v-else class="text-center py-8">
        <div class="text-6xl mb-6">📩</div>
        <h2 class="text-2xl font-black text-[#1A1A1A] mb-4">Link Enviado!</h2>
        <p class="text-gray-500 font-medium mb-10">Verifique sua caixa de entrada e a pasta de spam em <b>{{ email }}</b>.</p>
        
        <router-link to="/login" class="text-[#1A1A1A] font-black hover:underline uppercase tracking-widest text-sm">
          Voltar para o Login
        </router-link>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import iRota from '../assets/iRota.png'
import api from '../services/api'
import { validarFormatoDeEmail } from '../utils/validadoresDeFormatacao.js'

const email = ref('')
const loading = ref(false)
const linkEnviado = ref(false)

const emailEhValido = computed(() => validarFormatoDeEmail(email.value))
const formularioEstaValido = computed(() => email.value && emailEhValido.value && !loading.value)

const solicitarLink = async () => {
  if (!formularioEstaValido.value) {
    alert("Por favor, preencha um endereço de e-mail válido!");
    return
  }

  loading.value = true
  try {
    // Esse endpoint deve estar configurado no seu Laravel (Fortify ou manualmente)
    await api.post('/esqueceu-senha', { email: email.value })
    linkEnviado.value = true
  } catch (error) {
    console.error(error)
    alert("Erro ao enviar e-mail. Verifique se o endereço está correto.")
  } finally {
    loading.value = false
  }
}
</script>