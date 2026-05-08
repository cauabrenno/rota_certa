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
      <span v-if="totalItensCarrinho > 0" class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold italic shadow-md">
        {{ totalItensCarrinho }}
      </span>
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
          <span v-if="totalItensCarrinho > 0" class="absolute top-1 right-0 bg-[#C2F2D9] text-[#1A1A1A] text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black shadow-md">
            {{ totalItensCarrinho }}
          </span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Cesta</span>
      </button>

      <router-link to="/perfil" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">👤</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Perfil</span>
      </router-link>
    </div>

    <main class="p-4 md:p-6 lg:p-12 max-w-3xl mx-auto space-y-8 mt-2 relative z-10">
      
      <div v-if="carregando" class="text-center py-10 opacity-50 font-black uppercase tracking-widest">
        Carregando seus pedidos...
      </div>

      <section v-if="!carregando && pedidosAtivos.length > 0">
        <h2 class="text-xl font-black italic tracking-tighter uppercase mb-4 pl-2">Acompanhando</h2>
        
        <div v-for="pedidoAtivo in pedidosAtivos" :key="pedidoAtivo.id" @click="abrirDetalhes(pedidoAtivo)" class="bg-white rounded-[2.5rem] p-6 lg:p-8 shadow-2xl border border-black/5 relative overflow-hidden cursor-pointer hover:shadow-xl transition-all group mb-8">
          
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
                {{ pedidoAtivo.statusIndex >= 2 ? 'O entregador está a caminho' : 'Preparando seu pedido' }}
              </span>
            </div>
          </div>
          
          <div class="mt-4 text-center">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-[#2D4483] transition-colors">Ver detalhes completos ➔</span>
          </div>
        </div>
      </section>

      <div class="h-px bg-gray-200 w-full my-8"></div>

      <section v-if="!carregando">
        <h2 class="text-xl font-black italic tracking-tighter uppercase mb-4 pl-2">Histórico</h2>
        
        <div v-if="historico.length === 0" class="text-center py-6 text-gray-400 font-bold text-xs uppercase tracking-widest">
          Nenhum pedido no histórico ainda.
        </div>

        <div class="space-y-4 relative z-0">
          <div v-for="pedido in historico" :key="pedido.id" @click="abrirDetalhes(pedido)" class="bg-white p-5 lg:p-6 rounded-3xl shadow-sm border border-black/5 hover:shadow-md transition-all group cursor-pointer">
            
            <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ pedido.data }}</p>
              
              <!-- Selo Verde ou Vermelho Dinâmico -->
              <div class="flex items-center gap-1 px-2 py-1 rounded-lg" :class="pedido.statusIndex === 5 ? 'bg-red-50' : 'bg-green-50'">
                <span v-if="pedido.statusIndex === 5" class="text-red-600 text-[10px]">✕</span>
                <span v-else class="text-green-600 text-[10px]">✓</span>
                
                <span :class="pedido.statusIndex === 5 ? 'text-red-700' : 'text-green-700'" class="text-[9px] font-black uppercase tracking-widest">
                  {{ pedido.statusIndex === 5 ? 'Cancelado' : 'Concluído' }}
                </span>
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
              </div>
              
              <div class="flex gap-3 w-full md:w-auto z-10">
                <button @click.stop="alertAjuda" class="flex-1 md:flex-none py-2.5 px-4 bg-gray-100 text-[#1A1A1A] font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-gray-200 transition-all">
                  Ajuda
                </button>
                <button v-if="pedido.statusIndex !== 5" @click.stop="pedirDeNovo" class="flex-1 md:flex-none py-2.5 px-6 bg-[#C2F2D9] text-[#1A1A1A] font-black text-[10px] uppercase tracking-widest rounded-xl hover:scale-105 active:scale-95 transition-all shadow-sm">
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
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ pedidoSelecionado.id }}</p>
            <h2 class="text-2xl font-black italic tracking-tighter uppercase leading-tight text-[#1A1A1A]">{{ pedidoSelecionado.loja }}</h2>
            <p class="text-[10px] text-[#2D4483] font-bold mt-1">{{ pedidoSelecionado.data || 'Hoje, ' + pedidoSelecionado.previsao }}</p>
          </div>
        </div>

        <div v-if="pedidoSelecionado.statusIndex !== undefined && pedidoSelecionado.statusIndex < 4" class="mb-6 p-5 md:p-6 bg-gray-50 rounded-3xl border border-black/5 relative z-10">
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
              <span>R$ {{ (pedidoSelecionado.total - (pedidoSelecionado.taxaEntrega || 7.00)).toFixed(2) }}</span>
            </div>
            <div class="flex justify-between text-xs font-medium text-gray-600 gap-2">
              <span>Taxa de Entrega</span>
              <span>R$ {{ (pedidoSelecionado.taxaEntrega || 7.00).toFixed(2) }}</span>
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
          </div>

        </div>

        <!-- BOTÕES DINÂMICOS DO MODAL -->
        <div class="flex flex-col sm:flex-row gap-3 md:gap-4 mt-8 md:mt-0">
          <button @click="fecharDetalhes" class="w-full py-4.5 md:py-5 bg-gray-100 text-[#1A1A1A] rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-gray-200 transition-all">
            Fechar
          </button>

          <!-- Botão de Cancelar (Aparece só no status 0 = Aceito) -->
          <button 
            v-if="pedidoSelecionado.statusIndex === 0" 
            @click="cancelarPedido(pedidoSelecionado)" 
            class="w-full py-4.5 md:py-5 bg-red-50 text-red-600 border border-red-100 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-sm hover:bg-red-100 transition-all">
            Cancelar Pedido
          </button>

          <!-- Botão de Pedir de Novo (Aparece se for 4=Entregue) -->
          <button 
            v-else-if="pedidoSelecionado.statusIndex === 4" 
            @click="pedirDeNovo" 
            class="w-full py-4.5 md:py-5 bg-[#1A1A1A] text-[#C2F2D9] rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl hover:bg-black transition-all">
            Adicionar à Cesta
          </button>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import iRota from '../assets/iRota.png'
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api' 

const router = useRouter()

const etapas = ['Aceito', 'Preparo', 'Saiu', 'Perto', 'Entregue']

const carregando = ref(true)
const todosPedidos = ref([])

// === CARRINHO NO NAVBAR ===
const carrinho = ref(JSON.parse(localStorage.getItem('carrinho') || '[]'))
const totalItensCarrinho = computed(() => {
  return carrinho.value.reduce((total, item) => total + item.quantidade, 0)
})

// === SEPARANDO ATIVOS E HISTÓRICO ===
const pedidosAtivos = computed(() => {
  return todosPedidos.value.filter(p => p.statusIndex < 4)
})

const historico = computed(() => {
  // Pega os status >= 4 (Entregues ou Cancelados)
  return todosPedidos.value.filter(p => p.statusIndex >= 4)
})

const mapearStatusParaIndex = (status) => {
  const s = (status || '').toLowerCase()
  if (s.includes('cancelado')) return 5 // Novo Status!
  if (s.includes('entregue') || s.includes('conclu')) return 4
  if (s.includes('perto')) return 3
  if (s.includes('saiu') || s.includes('caminho')) return 2
  if (s.includes('preparo') || s.includes('preparando')) return 1
  return 0 
}

const formatarDataBR = (dataIso) => {
  if (!dataIso) return ''
  const d = new Date(dataIso)
  return d.toLocaleDateString('pt-BR', { weekday: 'short', day: '2-digit', month: '2-digit', year: 'numeric' })
}

const carregarPedidos = async () => {
  try {
    const res = await api.get('/meus-pedidos')
    
    todosPedidos.value = res.data.pedidos.map(p => ({
      dbId: p.id, // O ID real do banco para conseguirmos cancelar!
      id: `#RC-${p.id.toString().padStart(4, '0')}`,
      
      // ✨ AQUI ESTÁ A MÁGICA: Pegando o Nome e a Logo do Back-end!
      loja: p.loja?.nome || 'Loja Parceira', 
      logo: p.loja?.logo || 'https://cdn-icons-png.flaticon.com/512/1384/1384063.png', // Mantém o ícone padrão se o lojista não tiver cadastrado a foto ainda
      
      itensCount: p.produtos ? p.produtos.length : 0,
      total: parseFloat(p.valor_total),
      taxaEntrega: parseFloat(p.taxa_entrega),
      previsao: '45-60 min',
      codigo: p.codigo_entrega,
      statusIndex: mapearStatusParaIndex(p.status),
      pagamento: p.forma_pagamento,
      data: formatarDataBR(p.created_at),
      descricaoItens: p.descricao,
      itens: p.produtos ? p.produtos.map(prod => ({
        nome: prod.nome,
        qtd: prod.pivot.quantidade,
        preco: parseFloat(prod.pivot.preco_unitario)
      })) : []
    }))
  } catch (error) {
    console.error("Erro ao buscar histórico de pedidos", error)
  } finally {
    carregando.value = false
  }
}

onMounted(() => {
  carregarPedidos()
})

const pedidoSelecionado = ref(null)

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
  alert("Em breve! Esta função vai recriar o carrinho com estes itens.")
}

const irParaCarrinho = () => {
  router.push('/carrinho')
}

// === FUNÇÃO DE CANCELAR O PEDIDO ===
const cancelarPedido = async (pedido) => {
  if (confirm("Tem certeza que deseja cancelar este pedido?")) {
    try {
      // ✨ Mudamos a rota para bater na porta exclusiva do cliente!
      await api.put(`/pedidos/${pedido.dbId}/cancelar`)
      
      alert("Pedido cancelado com sucesso!")
      fecharDetalhes()
      carregarPedidos() // Atualiza a tela puxando tudo do banco de novo
    } catch (error) {
      console.error("Erro ao cancelar:", error)
      // Mostra a mensagem exata do back-end se o restaurante já tiver começado
      const msgErro = error.response?.data?.message || "Erro ao cancelar. Tente novamente."
      alert(msgErro)
    }
  }
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
@media (max-width: 380px) {
  span.uppercase {
    font-size: 7px !important;
  }
}
</style>