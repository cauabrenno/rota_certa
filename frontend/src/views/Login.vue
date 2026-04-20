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

  <div class="mb-10 text-center md:text-left">
    <h2 class="text-4xl font-black text-[#1A1A1A] tracking-tighter mb-2">Bem-vindo!</h2>
    <p class="text-[#1A1A1A]/60 font-medium italic">RotaCerta</p>
  </div>
      </div>

      <div class="w-full md:w-1/2 p-10 lg:p-20 flex flex-col justify-center bg-white/40">
        <div class="mb-10 text-center md:text-left">
          <h2 class="text-4xl font-black text-[#1A1A1A] tracking-tighter mb-2">Bem-vindo!</h2>
          <p class="text-[#1A1A1A]/60 font-medium italic">Acesse sua conta no RotaCerta</p>
        </div>
        
        <form @submit.prevent="handleLogin" class="space-y-6">
          <BaseInput label="Seu E-mail" v-model="email" type="email" placeholder="email@exemplo.com" />
          
          <div>
            <BaseInput label="Sua Senha" v-model="senha" type="password" placeholder="••••••••" />
            <div class="text-right mt-3">
              <router-link to="/esqueci-senha" class="text-sm text-[#2D4483] hover:text-black font-bold transition-colors">
                Esqueceu a senha?
              </router-link>
            </div>
          </div>

          <button type="submit" class="w-full py-5 bg-[#1A1A1A] text-white font-black text-lg rounded-2xl hover:bg-black transition-all shadow-xl uppercase tracking-widest mt-4">
            Entrar no Sistema
          </button>

          <div class="text-center mt-10">
            <p class="text-[#1A1A1A]/60 font-medium">Ainda não tem acesso?</p>
            
            <router-link to="/selecao">
              <button type="button" class="mt-4 px-12 py-3 border-2 border-[#1A1A1A] text-[#1A1A1A] font-black rounded-2xl hover:bg-[#1A1A1A] hover:text-white transition-all duration-300 shadow-md">
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
import BaseInput from '../components/BaseInput.vue'

const router = useRouter()
const email = ref('')
const senha = ref('')

const handleLogin = () => {
  if (!email.value || !senha.value) {
    alert("Por favor, preencha tudo!");
    return;
  }

  const userEmail = email.value.toLowerCase();

  if (userEmail.includes('entregador')) {
    router.push('/painel-entregador');
  } else if (userEmail.includes('cliente')) {
    router.push('/home'); 
  } else {
    router.push('/dashboard-lojista');
  }
}
</script>