<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] pb-32 md:pb-24 text-[#1A1A1A] relative overflow-x-hidden">
    
<nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-6 md:px-8">
  <div class="flex items-center gap-3">
    <img :src="iRota" alt="Rota Certa" class="w-10 h-10 object-contain drop-shadow-sm">
    <h1 class="text-2xl font-black italic uppercase tracking-tighter">Meus pedidos</h1>
  </div>
  
  <div class="hidden md:flex gap-6 items-center">
    <router-link title="Início" to="/home" class="text-2xl hover:scale-110 transition-all opacity-100">🏠</router-link>
    <router-link title="Meus Pedidos" to="/meus-pedidos" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">📄</router-link>
    <router-link title="Meu Perfil" to="/perfil" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">👤</router-link>
    <button @click="irParaCarrinho" class="relative text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">
      🛒
      <span class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold italic shadow-md">3</span>
    </button>
  </div>
</nav>

    <div class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 w-[92%] max-w-[400px] bg-[#1A1A1A] rounded-[2rem] shadow-[0_10px_50px_rgba(0,0,0,0.4)] border border-white/10 flex justify-between items-center px-6 py-4 z-50">
      <router-link to="/home" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">🏠</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Início</span>
      </router-link>

      <router-link to="/meus-pedidos" class="flex flex-col items-center gap-1 text-[#C2F2D9]">
        <div class="bg-[#C2F2D9]/20 p-2 rounded-xl transition-all"><span class="text-2xl">📄</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Pedidos</span>
      </router-link>

      <button @click="irParaCarrinho" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all relative">
        <div class="p-2 relative">
          <span class="text-2xl grayscale opacity-80">🛒</span>
          <span class="absolute top-1 right-0 bg-[#C2F2D9] text-[#1A1A1A] text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black shadow-md">3</span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Cesta</span>
      </button>

      <router-link to="/perfil" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">👤</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Perfil</span>
      </router-link>
    </div>

    <main class="p-4 md:p-6 lg:p-12 max-w-3xl mx-auto space-y-8 mt-2 relative z-10">
      
      <section v-if="pedidoAtivo">
        <h2 class="text-xl font-black italic tracking-tighter uppercase mb-4 pl-2">Acompanhar Pedido</h2>
        
        <div @click="abrirDetalhes(pedidoAtivo)" class="bg-white rounded-[2.5rem] p-6 lg:p-8 shadow-2xl border border-black/5 relative overflow-hidden cursor-pointer hover:shadow-xl transition-all group">
          
          <div class="flex justify-between items-start mb-6">
            <div>
              <p class="text-[10px] font-black uppercase tracking-widest text-[#2D4483] mb-1">Previsão: {{ pedidoAtivo.previsao }}</p>
              <h3 class="text-2xl font-black uppercase leading-tight">{{ pedidoAtivo.loja }}</h3>
              <p class="text-xs text-gray-500 font-medium mt-1">{{ pedidoAtivo.itensCount }} itens • R$ {{ pedidoAtivo.total.toFixed(2) }}</p>
            </div>
            <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200 group-hover:scale-110 transition-transform">
              <img :src="pedidoAtivo.logo" class="w-8 h-8 object-contain" />
            </div>
          </div>

          <div class="bg-[#1A1A1A] rounded-2xl p-4 lg:p-5 flex justify-between items-center mb-8 shadow-inner relative overflow-hidden z-10">
            <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/5 rounded-full blur-md"></div>
            <div>
              <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Código de Entrega</p>
              <p class="text-[10px] text-gray-500 w-3/4">Informe ao entregador.</p>
            </div>
            <div class="bg-white/10 px-4 py-2 rounded-xl backdrop-blur-md border border-white/10 flex items-center justify-center">
              <span class="text-2xl lg:text-3xl font-black tracking-widest text-[#C2F2D9]">{{ pedidoAtivo.codigo }}</span>
            </div>
          </div>

<div class="mb-12 md:mb-8 relative z-10">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4 text-center">Status do Pedido</h4>
            <div class="relative flex justify-between items-center px-1 md:px-4">
              <div class="absolute left-0 top-3 w-full h-1 bg-gray-100 -z-10 rounded-full"></div>
              <div class="absolute left-0 top-3 h-1 bg-[#2D4483] -z-10 rounded-full transition-all duration-500" :style="`width: ${(pedidoAtivo.statusIndex / (etapas.length - 1)) * 100}%`"></div>
              
              <div v-for="(etapa, idx) in etapas" :key="idx" class="flex flex-col items-center relative">
                <div :class="idx <= pedidoAtivo.statusIndex ? 'bg-[#2D4483] text-white shadow-md scale-110' : 'bg-gray-200 text-gray-400'" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all z-10">
                  <span v-if="idx < pedidoAtivo.statusIndex">✓</span>
                  <span v-else-if="idx === pedidoAtivo.statusIndex">📍</span>
                </div>
                <span :class="idx === pedidoAtivo.statusIndex ? 'text-[#1A1A1A] font-black' : 'text-gray-400 font-bold'" class="text-[8px] uppercase tracking-widest text-center absolute top-8 left-1/2 -translate-x-1/2 w-16 leading-tight transition-all">
                  {{ etapa }}
                </span>
              </div>
            </div>
          </div>

          <div class="w-full h-32 bg-gray-200 rounded-2xl relative overflow-hidden border border-black/5 md:mt-4 z-0">
            <div class="absolute inset-0 opacity-30 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-cover"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center animate-bounce">
              <span class="text-3xl drop-shadow-md">🛵</span>
              <div class="w-4 h-1 bg-black/20 rounded-full mt-1 blur-[1px]"></div>
            </div>
            <div class="absolute bottom-2 left-0 right-0 text-center">
              <span class="bg-white/90 backdrop-blur-sm px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm">
                O entregador está a caminho
              </span>
            </div>
          </div>
          
          <div class="mt-4 text-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-[#2D4483] transition-colors">Ver detalhes completos ➔</span>
          </div>
        </div>
      </section>

      <div class="h-px bg-gray-200 w-full my-8"></div>

      <section>
        <h2 class="text-xl font-black italic tracking-tighter uppercase mb-4 pl-2">Histórico</h2>
        
        <div class="space-y-4 relative z-0">
          <div v-for="pedido in historico" :key="pedido.id" @click="abrirDetalhes(pedido)" class="bg-white p-5 lg:p-6 rounded-3xl shadow-sm border border-black/5 hover:shadow-md transition-all group cursor-pointer">
            
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ pedido.data }}</p>
              <div class="flex items-center gap-1 bg-green-50 px-2 py-1 rounded-lg">
                <span class="text-green-600 text-[10px]">✓</span>
                <span class="text-[9px] font-black text-green-700 uppercase tracking-widest">Concluído</span>
              </div>
            </div>

            <div class="flex items-start gap-4">
              <div class="w-12 h-12 bg-gray-50 rounded-xl flex items-center justify-center border border-gray-100 p-1 group-hover:scale-105 transition-transform">
                <img :src="pedido.logo" class="w-full h-full object-contain" />
              </div>
              <div class="flex-1">
                <h4 class="font-black text-sm uppercase">{{ pedido.loja }}</h4>
                <p class="text-xs text-gray-500 font-medium leading-tight mt-1 line-clamp-2">
                  {{ pedido.descricaoItens }}
                </p>
              </div>
            </div>

            <div class="mt-4 pt-4 border-t border-gray-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
              <div class="flex items-center gap-2">
                <span class="text-lg font-black text-[#1A1A1A]">R$ {{ pedido.total.toFixed(2) }}</span>
                <span v-if="pedido.economia" class="text-[9px] font-black text-[#2D4483] uppercase tracking-widest bg-[#E2F1F7] px-2 py-1 rounded-md">
                  Economizou R$ {{ pedido.economia.toFixed(2) }}
                </span>
              </div>
              
              <div class="flex gap-3 w-full md:w-auto z-10">
                <button @click.stop="alertAjuda" class="flex-1 md:flex-none py-2.5 px-4 bg-gray-100 text-[#1A1A1A] font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-gray-200 transition-all">
                  Ajuda
                </button>
                <button @click.stop="pedirDeNovo" class="flex-1 md:flex-none py-2.5 px-6 bg-[#C2F2D9] text-[#1A1A1A] font-black text-[10px] uppercase tracking-widest rounded-xl hover:scale-105 active:scale-95 transition-all shadow-sm">
                  Pedir de Novo
                </button>
              </div>
            </div>
            
          </div>
        </div>
      </section>

    </main>

    <div v-if="pedidoSelecionado" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharDetalhes" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      
      <div class="relative bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-t-[2.5rem] md:rounded-[2.5rem] p-5 lg:p-10 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300 custom-scrollbar z-50">
        <button @click="fecharDetalhes" class="absolute top-4 right-4 lg:top-6 lg:right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200 flex items-center justify-center">✕</button>
        
        <div class="flex items-center gap-4 border-b border-gray-100 pb-5 md:pb-6 mb-5 md:mb-6">
          <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center border border-gray-100 p-2">
            <img :src="pedidoSelecionado.logo" class="w-full h-full object-contain" />
          </div>
          <div class="flex-1">
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pedido {{ pedidoSelecionado.id }}</p>
            <h2 class="text-2xl font-black italic tracking-tighter uppercase leading-tight text-[#1A1A1A]">{{ pedidoSelecionado.loja }}</h2>
            <p class="text-[10px] text-[#2D4483] font-bold mt-1">{{ pedidoSelecionado.data || 'Hoje, ' + pedidoSelecionado.previsao }}</p>
          </div>
        </div>

        <div v-if="pedidoSelecionado.statusIndex !== undefined" class="mb-6 p-5 md:p-6 bg-gray-50 rounded-3xl border border-black/5 relative z-10">
          <div class="flex justify-between items-center mb-6">
             <p class="text-[10px] font-black uppercase tracking-widest text-[#2D4483]">Previsão: {{ pedidoSelecionado.previsao }}</p>
             <div class="bg-white px-3 py-1 rounded-lg border border-black/5 shadow-sm flex items-center gap-2">
               <span class="text-[8px] font-black uppercase text-gray-400">Código</span>
               <span class="text-sm font-black text-[#1A1A1A]">{{ pedidoSelecionado.codigo }}</span>
             </div>
          </div>

        <div class="relative flex justify-between items-center px-1 md:px-4 mb-12 md:mb-8 z-10">
            <div class="absolute left-0 top-3 w-full h-1 bg-gray-200 -z-10 rounded-full"></div>
            <div class="absolute left-0 top-3 h-1 bg-[#2D4483] -z-10 rounded-full transition-all duration-500" :style="`width: ${(pedidoSelecionado.statusIndex / (etapas.length - 1)) * 100}%`"></div>
            
            <div v-for="(etapa, idx) in etapas" :key="idx" class="flex flex-col items-center relative">
              <div :class="idx <= pedidoSelecionado.statusIndex ? 'bg-[#2D4483] text-white shadow-md scale-110' : 'bg-white text-gray-300 border border-gray-200'" class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black transition-all z-10">
                <span v-if="idx < pedidoSelecionado.statusIndex">✓</span>
                <span v-else-if="idx === pedidoSelecionado.statusIndex">📍</span>
              </div>
              <span :class="idx === pedidoSelecionado.statusIndex ? 'text-[#1A1A1A] font-black' : 'text-gray-400 font-bold'" class="text-[8px] uppercase tracking-widest text-center absolute top-8 left-1/2 -translate-x-1/2 w-16 leading-tight transition-all">
                {{ etapa }}
              </span>
            </div>
          </div>

          <div class="w-full h-24 bg-gray-200 rounded-2xl relative overflow-hidden border border-black/5 z-0 mt-8 md:mt-0">
            <div class="absolute inset-0 opacity-30 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-cover"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center animate-bounce">
              <span class="text-2xl drop-shadow-md">🛵</span>
            </div>
          </div>
        </div>

        <div class="space-y-4 mb-6">
          <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400">Itens do Pedido</h3>
          
          <div v-for="(item, index) in pedidoSelecionado.itens" :key="index" class="flex justify-between items-center border-b border-gray-50 pb-3 last:border-0 last:pb-0 gap-3">
            <div class="flex items-center gap-2.5 flex-1">
              <span class="bg-gray-100 text-[#1A1A1A] w-6 h-6 flex items-center justify-center rounded-md font-black text-[10px] flex-shrink-0">{{ item.qtd }}x</span>
              <p class="font-bold text-xs uppercase leading-tight">{{ item.nome }}</p>
            </div>
            <span class="font-black text-[#1A1A1A] text-sm flex-shrink-0">R$ {{ (item.preco * item.qtd).toFixed(2) }}</span>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 md:gap-6 bg-gray-50 rounded-3xl p-5 md:p-6 border border-black/5 mb-6 md:mb-8">
          
          <div class="space-y-2.5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Resumo Financeiro</h3>
            <div class="flex justify-between text-xs font-medium text-gray-600 gap-2">
              <span>Subtotal</span>
              <span class="font-bold">R$ {{ (pedidoSelecionado.total - 7.00 + (pedidoSelecionado.economia || 0)).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-xs font-medium text-gray-600 gap-2">
              <span>Taxa de Entrega</span>
              <span class="font-bold">R$ 7.00</span>
            </div>
            <div v-if="pedidoSelecionado.economia" class="flex justify-between text-xs font-bold text-green-600 gap-2">
              <span>Descontos / Clube</span>
              <span>- R$ {{ pedidoSelecionado.economia.toFixed(2) }}</span>
            </div>
            <div class="pt-2 border-t border-gray-200 flex justify-between items-center mt-2.5 gap-2">
              <span class="font-black text-sm uppercase">Total</span>
              <span class="font-black text-lg lg:text-xl text-[#1A1A1A]">R$ {{ pedidoSelecionado.total.toFixed(2) }}</span>
            </div>
          </div>

          <div class="space-y-4 border-t md:border-t-0 md:border-l border-gray-200 pt-5 md:pt-0 md:pl-6">
            <div>
              <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Pagamento</h3>
              <p class="font-bold text-xs flex items-center gap-2 leading-tight"><span class="text-base flex-shrink-0">💳</span> {{ pedidoSelecionado.pagamento }}</p>
            </div>
            <div>
              <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1.5">Entregue em</h3>
              <p class="font-bold text-xs flex items-center gap-2 leading-tight"><span class="text-base flex-shrink-0">📍</span> {{ pedidoSelecionado.endereco }}</p>
            </div>
          </div>

        </div>

        <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mt-8 md:mt-0">
          <button @click="fecharDetalhes" class="w-full py-4.5 md:py-5 bg-gray-100 text-[#1A1A1A] rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-gray-200 transition-all">
            Fechar
          </button>
          <button @click="pedirDeNovo" class="w-full py-4.5 md:py-5 bg-[#1A1A1A] text-[#C2F2D9] rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl hover:bg-black transition-all">
            Adicionar à Cesta
          </button>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import iRota from '../assets/iRota.png'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import logoMix from '../assets/logoMix.png'
import atacadao from '../assets/atacadao.png'
import assai from '../assets/assai.png'

const router = useRouter()

const etapas = ['Aceito', 'Preparo', 'Saiu', 'Perto', 'Entregue']

// Estado do Modal
const pedidoSelecionado = ref(null)

// Funções do Modal
const abrirDetalhes = (pedido) => {
  pedidoSelecionado.value = pedido
}

const fecharDetalhes = () => {
  pedidoSelecionado.value = null
}

const alertAjuda = () => {
  alert("Redirecionando para a central de ajuda...")
}

const pedirDeNovo = () => {
  alert("Itens adicionados ao carrinho!")
  irParaCarrinho()
}

// Mock do Pedido Ativo
const pedidoAtivo = ref({
  id: '#RC-9842',
  loja: 'Mix Mateus',
  logo: logoMix,
  itensCount: 3,
  total: 45.80,
  previsao: '18:50 - 19:10',
  codigo: '7280',
  statusIndex: 2,
  pagamento: 'Pix',
  endereco: 'Rua das Flores, 123 - Centro',
  itens: [
    { nome: 'Café Santa Clara 250g', qtd: 2, preco: 11.90 },
    { nome: 'Açúcar União 1kg', qtd: 1, preco: 4.20 },
    { nome: 'Leite Integral Parmalat', qtd: 2, preco: 5.40 }
  ]
})

// Mock do Histórico
const historico = ref([
  {
    id: '#RC-8721',
    data: 'Qui, 19/03/2026',
    loja: 'Atacadão',
    logo: atacadao,
    descricaoItens: '1x Arroz Branco 1kg Camil, 2x Feijão Carioca Kicaldo, 1x Óleo de Soja',
    total: 34.50,
    economia: 4.20,
    pagamento: 'Cartão de Crédito final 4321',
    endereco: 'Rua das Flores, 123 - Centro',
    itens: [
      { nome: 'Arroz Branco 1kg Camil', qtd: 1, preco: 5.89 },
      { nome: 'Feijão Carioca Kicaldo', qtd: 2, preco: 8.90 },
      { nome: 'Óleo de Soja', qtd: 1, preco: 6.45 }
    ]
  },
  {
    id: '#RC-7533',
    data: 'Dom, 15/03/2026',
    loja: 'Assaí',
    logo: assai,
    descricaoItens: '1x Sabão Omo 800g, 2x Amaciante Downy',
    total: 56.30,
    economia: 8.90,
    pagamento: 'Pix',
    endereco: 'Av. Principal, 450 - Empresarial',
    itens: [
      { nome: 'Sabão Omo 800g', qtd: 1, preco: 16.50 },
      { nome: 'Amaciante Downy', qtd: 2, preco: 19.90 }
    ]
  }
])

const irParaCarrinho = () => {
  router.push('/carrinho')
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
.custom-scrollbar::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: #CBD5E1;
  border-radius: 20px;
}
/* Garante que o conteúdo do tracking não quebre layout em telas muito pequenas */
@media (max-width: 380px) {
  span.uppercase {
    font-size: 7px !important;
  }
}
</style>