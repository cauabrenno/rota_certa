<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] text-[#1A1A1A] font-sans pb-12">
    
  <nav class="w-full bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-8 mb-8">
      
      <div class="flex items-center gap-3">
        <img :src="iRota" alt="Rota Certa" class="w-10 h-10 object-contain drop-shadow-sm">
        <div>
          <h1 class="text-2xl font-black italic uppercase tracking-tighter leading-none">Painel do Lojista</h1>
          <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
            {{ $route.path === '/dashboard-lojista' ? 'Gestão de Pedidos' : ($route.path === '/lojista-produtos' ? 'Gestão de Produtos' : 'Configurações da Loja') }}
          </p>
        </div>
      </div>
      
      <div class="flex gap-6 items-center">
        <router-link 
          to="/dashboard-lojista" 
          class="text-sm font-black uppercase tracking-widest transition-colors"
          :class="$route.path === '/dashboard-lojista' ? 'text-[#2D4483]' : 'text-gray-400 hover:text-[#1A1A1A]'"
        >
          Dashboard
        </router-link>

        <router-link 
          to="/lojista-produtos" 
          class="text-sm font-black uppercase tracking-widest transition-colors"
          :class="$route.path === '/lojista-produtos' ? 'text-[#2D4483]' : 'text-gray-400 hover:text-[#1A1A1A]'"
        >
          Meus Produtos
        </router-link>

        <router-link 
          to="/lojista-perfil" 
          class="text-sm font-black uppercase tracking-widest transition-colors"
          :class="$route.path === '/lojista-perfil' ? 'text-[#2D4483]' : 'text-gray-400 hover:text-[#1A1A1A]'"
        >
          Meu Perfil
        </router-link>

        <button @click="fazerLogout" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-100 transition-all">
          Sair
        </button>
      </div>

    </nav>

    <main class="max-w-[1000px] mx-auto px-8">
      
      <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-black italic uppercase tracking-tighter text-[#1A1A1A]">Perfil da Loja</h2>
        <button @click="salvarPerfil" class="bg-[#2D4483] text-white px-8 py-4 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-blue-900 shadow-xl transition-all">
          Salvar Alterações
        </button>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        
        <div class="md:col-span-1 space-y-6">
          
          <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-black/5 flex flex-col items-center text-center">
            <div class="relative group cursor-pointer mb-6">
              <div class="w-40 h-40 rounded-full border-4 border-gray-50 shadow-inner overflow-hidden bg-gray-100 flex items-center justify-center">
                <img v-if="perfil.logo_loja" :src="perfil.logo_loja" class="w-full h-full object-cover" />
                <span v-else class="text-5xl opacity-20">🏪</span>
              </div>
              
              <label class="absolute inset-0 bg-black/50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                <span class="text-white font-black text-[10px] uppercase tracking-widest">Mudar Foto</span>
                <input type="file" accept="image/*" @change="handleLogo" class="hidden" />
              </label>
            </div>

            <h3 class="font-black text-lg uppercase leading-tight">{{ perfil.nome || 'Nome da Loja' }}</h3>
            <p class="text-[10px] text-gray-400 font-bold tracking-widest uppercase mt-1">CNPJ: {{ perfil.cnpj || '00.000.000/0000-00' }}</p>

            <div class="w-full h-px bg-gray-100 my-6"></div>

            <div class="w-full">
              <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-3">Status do Delivery</p>
              <button 
                @click="perfil.aberto = !perfil.aberto"
                :class="perfil.aberto ? 'bg-green-50 text-green-600 border-green-200' : 'bg-red-50 text-red-600 border-red-200'"
                class="w-full py-4 rounded-2xl border font-black uppercase text-xs tracking-widest transition-colors flex items-center justify-center gap-2"
              >
                <span class="w-2 h-2 rounded-full" :class="perfil.aberto ? 'bg-green-500' : 'bg-red-500'"></span>
                {{ perfil.aberto ? 'Loja Aberta' : 'Loja Fechada' }}
              </button>
            </div>
          </div>

        </div>

        <div class="md:col-span-2 space-y-8">
          
          <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-black/5">
            <h3 class="text-sm font-black italic uppercase tracking-tighter mb-6 text-[#2D4483]">Informações Básicas</h3>
            
            <div class="space-y-4">
              <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Nome do Estabelecimento</label>
                <input v-model="perfil.nome" type="text" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
              </div>

              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">CNPJ</label>
                  <input v-model="perfil.cnpj" type="text" v-mask="'##.###.###/####-##'" placeholder="00.000.000/0000-00" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
                </div>
                <div>
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Telefone / WhatsApp</label>
                  <input v-model="perfil.telefone" type="text" v-mask="'(##) #####-####'" placeholder="(88) 90000-0000" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-black/5">
            <h3 class="text-sm font-black italic uppercase tracking-tighter mb-6 text-[#2D4483]">Localização</h3>
            
            <div class="space-y-4">
              <div class="grid grid-cols-3 gap-4">
                <div class="col-span-1">
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">CEP</label>
                  <input v-model="perfil.endereco.cep" type="text" v-mask="'#####-###'" placeholder="63010-000" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
                </div>
                <div class="col-span-2">
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Cidade - Estado</label>
                  <input 
                    v-model="perfil.endereco.cidade" 
                    type="text" 
                    placeholder="Ex: Crato - CE" 
                    class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm text-[#1A1A1A] focus:border-[#1A1A1A] transition-all" 
                  />
                </div>
              </div>

              <div class="grid grid-cols-4 gap-4">
                <div class="col-span-3">
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Rua / Avenida</label>
                  <input v-model="perfil.endereco.rua" type="text" placeholder="Ex: Av. Padre Cícero" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
                </div>
                <div class="col-span-1">
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Número</label>
                  <input v-model="perfil.endereco.numero" type="text" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
                </div>
              </div>

              <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Bairro</label>
                <input v-model="perfil.endereco.bairro" type="text" placeholder="Ex: Centro" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
              </div>
            </div>
          </div>

        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import iRota from '../assets/iRota.png'
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api' // Seu Axios

const router = useRouter()

// DADOS DO PERFIL MOCKADOS (Prontos para API)
const perfil = ref({
  nome: '',
  cnpj: '',
  telefone: '',
  aberto: true,
  logo_loja: null,
  endereco: {
    cep: '',
    cidade: '',
    rua: '',
    numero: '',
    bairro: ''
  }
})

// Lida com o Upload da Foto
const handleLogo = (event) => {
  const file = event.target.files[0]
  if (!file) return

  const reader = new FileReader()
  reader.onload = (e) => {
    perfil.value.logo_loja = e.target.result
  }
  reader.readAsDataURL(file)
}

// Futura chamada pro Laravel
const buscarPerfil = async () => {
  try {
    const res = await api.get('/lojista/perfil')
    
    // Se a API retornar sucesso, a gente preenche a tela
    if (res.data) {
      perfil.value = res.data
    }
  } catch (error) {
    console.error("Erro ao puxar dados do banco:", error)
    alert("Erro ao carregar o seu perfil. Verifique o console (F12).")
  }
}

const salvarPerfil = async () => {
  try {
    console.log("Dados que estou enviando:", perfil.value); // Veja no console se o objeto está cheio
    
    const response = await api.post('/lojista/perfil', perfil.value);
    
    if (response.status === 200) {
      alert("Perfil salvo no banco de dados!");
      await buscarPerfil(); // Recarrega para ter certeza que veio do banco
    }
  } catch (error) {
    console.error("Erro detalhado:", error.response?.data);
    alert("Opa! O banco recusou os dados. Verifique o console.");
  }
}

const fazerLogout = () => {
  if(confirm("Deseja sair do painel do lojista?")) {
    localStorage.removeItem('token')
    router.push('/')
  }
}

onMounted(() => {
  buscarPerfil()
})
</script>