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

      <form @submit.prevent="lidarComCadastro" class="space-y-5">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <BaseInput label="Nome Completo" v-model="form.nome" tipoDeCampo="textoPuro" placeholder="Seu nome aqui" />
          </div>
          <div>
            <BaseInput label="E-mail" v-model="form.email" type="email" placeholder="email@exemplo.com" />
            <p v-if="form.email && !emailEhValido" class="text-red-500 text-xs font-bold mt-1 ml-1">E-mail com formato inválido</p>
          </div>
          
          <div class="md:col-span-2">
            <BaseInput label="Telefone / WhatsApp" v-model="form.telefone" tipoDeCampo="telefone" placeholder="(00) 90000-0000" />
          </div>
          
          <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-1.5 ml-1">Senha</label>
            <div class="relative w-full">
              <input 
                :type="mostrarSenha ? 'text' : 'password'" 
                v-model="form.senha" 
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
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 01-1.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                </svg>
              </button>
            </div>
            <p v-if="form.senha && form.senha.length < 8" class="text-red-500 text-xs font-bold mt-1 ml-1">Senha deve ter no mínimo 8 caracteres</p>
          </div>

          <div>
            <label class="block text-sm font-bold text-[#1A1A1A] mb-1.5 ml-1">Confirmar Senha</label>
            <div class="relative w-full">
              <input 
                :type="mostrarConfirmarSenha ? 'text' : 'password'" 
                v-model="form.confirmarSenha" 
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
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 01-1.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                </svg>
              </button>
            </div>
            <p v-if="form.confirmarSenha && form.senha !== form.confirmarSenha" class="text-red-500 text-xs font-bold mt-1 ml-1">As senhas não coincidem</p>
          </div>
          
        </div>

        <div v-if="isFuncionario" class="space-y-5 pt-4 border-t border-black/5 mt-4">
          
          <div>
            <BaseInput label="CPF (Dono da Conta)" v-model="form.cpf" tipoDeCampo="cpf" placeholder="000.000.000-00" />
            <p v-if="form.cpf && !cpfEhValido" class="text-red-500 text-xs font-bold mt-1 ml-1">CPF inválido</p>
          </div>

          <div v-if="tipoFuncionario === 'lojista'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <BaseInput label="CNPJ" v-model="form.cnpj" placeholder="00.000.000/0000-00" />
            <BaseInput label="Endereço da Empresa" v-model="form.endereco" placeholder="Rua, Bairro - Cidade/CE" />
          </div>

          <div v-if="tipoFuncionario === 'entregador'" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <BaseInput label="CNH" v-model="form.cnh" placeholder="Número da CNH" />
          </div>

        </div>

        <button 
          type="submit" 
          :disabled="!formularioEstaValido"
          :class="[!formularioEstaValido ? 'opacity-50 cursor-not-allowed' : 'hover:bg-black hover:scale-[1.02] active:scale-95']"
          class="w-full py-5 bg-[#1A1A1A] text-white font-black text-lg rounded-2xl transition-all shadow-xl mt-6 uppercase tracking-widest"
        >
          Finalizar Cadastro
        </button>

        <div class="text-center mt-6">
          <router-link to="/login" class="text-[#1A1A1A] font-black text-sm hover:underline uppercase tracking-tighter">
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
import BaseInput from '../components/BaseInput.vue' 
import api from '../services/api' 
import { 
  validarFormatoDeEmail, 
  validarAlgoritmoDeCadastroDePessoasFisicas 
} from '../utils/validadoresDeFormatacao.js'

const mostrarSenha = ref(false)
const mostrarConfirmarSenha = ref(false)

const route = useRoute()
const router = useRouter()

const isFuncionario = computed(() => route.query.perfil === 'funcionario')
const tipoFuncionario = ref('lojista')

// Variáveis de Lojista e Entregador adicionadas no form inicial
const form = ref({
  nome: '', 
  email: '', 
  telefone: '', 
  senha: '', 
  confirmarSenha: '', 
  cpf: '', 
  cnh: '',
  cnpj: '',
  endereco: ''
})

const emailEhValido = computed(() => validarFormatoDeEmail(form.value.email))
const cpfEhValido = computed(() => validarAlgoritmoDeCadastroDePessoasFisicas(form.value.cpf))

const formularioEstaValido = computed(() => {
  if (!form.value.nome || !form.value.email || !form.value.telefone || !form.value.senha || !form.value.confirmarSenha) {
    return false
  }
  
  if (!emailEhValido.value) {
    return false
  }
  
  if (form.value.senha.length < 8) {
    return false
  }
  
  if (form.value.senha !== form.value.confirmarSenha) {
    return false
  }
  
  if (isFuncionario.value) {
    if (!form.value.cpf || !cpfEhValido.value) {
      return false
    }
    
    if (tipoFuncionario.value === 'lojista') {
      if (!form.value.cnpj || !form.value.endereco) {
        return false
      }
    }
    
    if (tipoFuncionario.value === 'entregador') {
      if (!form.value.cnh) {
        return false
      }
    }
  }
  
  return true
})

const lidarComCadastro = async () => {
  if (!formularioEstaValido.value) {
    alert("Por favor, preencha todos os campos e corrija os erros de validação antes de prosseguir!");
    return
  }

  const tipoUsuario = isFuncionario.value ? tipoFuncionario.value : 'cliente';

  try {
    const payload = {
      name: form.value.nome,
      email: form.value.email,
      telefone: form.value.telefone,
      password: form.value.senha,
      password_confirmation: form.value.confirmarSenha,
      tipo_usuario: tipoUsuario,
      cpf: form.value.cpf, 
      cnh: form.value.cnh,
      cnpj: form.value.cnpj,
      endereco: form.value.endereco
    }

    const response = await api.post('/register', payload)

    alert("Cadastro realizado com sucesso! Bem-vindo ao RotaCerta.");
    
    router.push('/login'); 

  } catch (error) {
    console.error("Erro ao cadastrar:", error)
    
    if (error.response && error.response.status === 422) {
      alert("Erro nos dados: Verifique se este e-mail já está em uso ou se a senha tem no mínimo 8 caracteres.");
    } else {
      alert("Erro no servidor. O 'php artisan serve' está rodando no outro terminal?");
    }
  }
}
</script>
