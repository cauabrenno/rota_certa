<template>
  <div class="min-h-screen bg-gradient-to-br from-[#C2F2D9] to-[#91d1b0] flex items-center justify-center p-4">
    
    <div class="bg-white/80 backdrop-blur-2xl border border-white/50 rounded-[3rem] shadow-2xl p-10 lg:p-16 max-w-2xl w-full">
      
      <div class="mb-10 text-center">
        <h2 class="text-4xl font-black text-[#1A1A1A] tracking-tighter mb-2 italic">Crie sua conta</h2>
        <p class="text-[#1A1A1A]/60 font-medium">Preencha os dados para começar no RotaCerta</p>
      </div>

      <div v-if="isFuncionario" class="flex bg-black/5 rounded-2xl p-1.5 mb-8 border border-black/10">
        <button 
          @click="tipoFuncionario = 'lojista'"
          type="button"
          :class="tipoFuncionario === 'lojista' ? 'bg-[#1A1A1A] text-white shadow-lg' : 'text-[#1A1A1A]/50 hover:text-[#1A1A1A]'"
          class="flex-1 py-4 rounded-xl font-black uppercase text-xs tracking-widest transition-all duration-300"
        >
          Lojista
        </button>
        <button 
          @click="tipoFuncionario = 'entregador'"
          type="button"
          :class="tipoFuncionario === 'entregador' ? 'bg-[#1A1A1A] text-white shadow-lg' : 'text-[#1A1A1A]/50 hover:text-[#1A1A1A]'"
          class="flex-1 py-4 rounded-xl font-black uppercase text-xs tracking-widest transition-all duration-300"
        >
          Entregador
        </button>
      </div>

      <form @submit.prevent="handleCadastro" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <BaseInput label="Nome Completo" v-model="form.nome" placeholder="Seu nome aqui" />
          <BaseInput label="E-mail" v-model="form.email" type="email" placeholder="email@exemplo.com" />
          <BaseInput label="Senha" v-model="form.senha" type="password" placeholder="••••••••" />
          <BaseInput label="Confirmar Senha" v-model="form.confirmarSenha" type="password" placeholder="••••••••" />
        </div>

        <div v-if="isFuncionario" class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4 border-t border-black/5">
          <BaseInput label="CPF" v-model="form.cpf" placeholder="000.000.000-00" />
          <BaseInput v-if="tipoFuncionario === 'entregador'" label="CNH" v-model="form.cnh" placeholder="Número da CNH" />
        </div>

        <button type="submit" class="w-full py-5 bg-[#1A1A1A] text-white font-black text-lg rounded-2xl hover:bg-black hover:scale-[1.02] active:scale-95 transition-all shadow-xl mt-6 uppercase tracking-widest">
          Finalizar Cadastro
        </button>

        <div class="text-center mt-6">
          <router-link to="/" class="text-[#1A1A1A] font-black text-sm hover:underline uppercase tracking-tighter">
            Já tenho conta? Ir para o Login
          </router-link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import BaseInput from '../components/BaseInput.vue' // <-- O HERÓI QUE ESTAVA FALTANDO!
import api from '../services/api' 

const route = useRoute()
const router = useRouter()

// Verifica se veio da tela de "Sou Funcionário" ou "Sou Cliente"
const isFuncionario = computed(() => route.query.perfil === 'funcionario')
const tipoFuncionario = ref('lojista')

const form = ref({
  nome: '', email: '', senha: '', confirmarSenha: '', cpf: '', cnh: ''
})

const handleCadastro = async () => {
  // Validação básica do Front-end
  if (form.value.senha !== form.value.confirmarSenha) {
    alert("As senhas não coincidem!");
    return;
  }

  // Define o tipo exato que o banco de dados PostgreSQL está esperando
  const tipoUsuario = isFuncionario.value ? tipoFuncionario.value : 'cliente';

  try {
    // Monta o "pacote" com os nomes das colunas exatamente como o Laravel espera
    const payload = {
      name: form.value.nome,
      email: form.value.email,
      password: form.value.senha,
      password_confirmation: form.value.confirmarSenha,
      tipo: tipoUsuario,
      cpf: form.value.cpf, 
      cnh: form.value.cnh
    }

    // Bate na porta de registro do back-end
    const response = await api.post('/register', payload)

    alert("Cadastro realizado com sucesso! Bem-vindo ao RotaCerta.");
    
    // Sucesso! Joga o usuário de volta para a tela de Login
    router.push('/login'); 

  } catch (error) {
    console.error("Erro ao cadastrar:", error)
    
    // Se o Laravel devolver erro de validação
    if (error.response && error.response.status === 422) {
      alert("Erro nos dados: Verifique se este e-mail já está em uso ou se a senha tem no mínimo 8 caracteres.");
    } else {
      alert("Erro no servidor. O 'php artisan serve' está rodando no outro terminal?");
    }
  }
}
</script>