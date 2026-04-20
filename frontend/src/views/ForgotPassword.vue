<template>
  <div class="min-h-screen bg-gradient-to-br from-[#C2F2D9] to-[#91d1b0] flex items-center justify-center p-4 relative overflow-hidden">
    
    <div class="absolute -top-16 -left-16 w-64 h-64 bg-[#1A1A1A] rounded-full opacity-10"></div>
    <div class="absolute top-1/2 -right-20 w-48 h-48 bg-[#1A1A1A] rounded-full opacity-10"></div>
    <div class="absolute -bottom-10 left-1/4 w-40 h-40 bg-[#1A1A1A] rounded-full opacity-10"></div>

    <div class="bg-white/70 backdrop-blur-2xl border border-white/50 p-10 rounded-[3rem] shadow-2xl w-full max-w-lg z-10">
      
      <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-[#1A1A1A] tracking-tighter uppercase">Redefinir Senha</h2>
        <p class="text-[#1A1A1A]/60 font-medium text-sm mt-2">Informe seu e-mail para recuperar o acesso</p>
      </div>

      <form @submit.prevent="handleReset" class="space-y-5">
        <BaseInput 
          label="Informe seu e-mail:" 
          v-model="email" 
          type="email" 
          placeholder="exemplo@email.com" 
        />

        <div class="pt-4 border-t border-[#1A1A1A]/10 space-y-5">
          <BaseInput 
            label="Digite sua nova senha:" 
            v-model="novaSenha" 
            type="password" 
            placeholder="••••••••" 
          />
          <BaseInput 
            label="Confirme sua senha:" 
            v-model="confirmarSenha" 
            type="password" 
            placeholder="••••••••" 
          />
        </div>

        <button 
          type="submit"
          class="w-full py-5 bg-[#1A1A1A] text-white font-black text-lg rounded-2xl mt-6 hover:bg-black hover:scale-[1.02] active:scale-95 transition-all shadow-xl shadow-black/20 uppercase tracking-widest"
        >
          Redefinir
        </button>

        <div class="text-center mt-6">
          <router-link to="/" class="text-[#2D4483] font-black text-sm hover:underline uppercase tracking-tighter">
            Voltar para o Login
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseInput from '../components/BaseInput.vue'

const router = useRouter()
const email = ref('')
const novaSenha = ref('')
const confirmarSenha = ref('')

const handleReset = () => {
  if (!email.value || !novaSenha.value || !confirmarSenha.value) {
    alert("Por favor, preencha todos os campos!");
    return;
  }

  if (novaSenha.value !== confirmarSenha.value) {
    alert("As senhas não coincidem!");
    return;
  }

  alert("Senha redefinida com sucesso! Você será levado ao login.");
  router.push('/');
}
</script>