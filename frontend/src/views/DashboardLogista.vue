<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] text-[#1A1A1A] font-sans pb-12">
    
    <!-- NAVBAR DO LOJISTA -->
    <nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-8 mb-8">
      <div class="flex items-center gap-3">
        <img :src="iRota" alt="Rota Certa" class="w-10 h-10 object-contain drop-shadow-sm">
        <div>
          <h1 class="text-2xl font-black italic uppercase tracking-tighter leading-none">Painel do Lojista</h1>
          <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Gestão de Pedidos</p>
        </div>
      </div>
      
      <div class="flex gap-6 items-center">
        <router-link to="/dashboard-lojista" class="text-sm font-black uppercase tracking-widest text-[#2D4483]">Dashboard</router-link>
        <router-link to="/lojista-produtos" class="text-sm font-black uppercase tracking-widest text-gray-400 hover:text-[#1A1A1A] transition-colors">Meus Produtos</router-link>
        <button @click="fazerLogout" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-100 transition-all">
          Sair
        </button>
      </div>
    </nav>

    <main class="max-w-[1400px] mx-auto px-8 space-y-8">
      
      <!-- CARDS DE RESUMO (KPIs) -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="bg-white p-6 rounded-3xl shadow-lg border border-black/5 flex flex-col justify-between hover:-translate-y-1 transition-transform">
          <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Novos Pedidos</p>
          <span class="text-4xl font-black text-[#2D4483]">{{ resumo.pendentes }}</span>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-lg border border-black/5 flex flex-col justify-between hover:-translate-y-1 transition-transform">
          <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Em Preparo</p>
          <span class="text-4xl font-black text-yellow-500">{{ resumo.emPreparo }}</span>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-lg border border-black/5 flex flex-col justify-between hover:-translate-y-1 transition-transform">
          <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Saiu p/ Entrega</p>
          <span class="text-4xl font-black text-orange-500">{{ resumo.emEntrega }}</span>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-lg border border-black/5 flex flex-col justify-between hover:-translate-y-1 transition-transform">
          <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-2">Finalizados Hoje</p>
          <span class="text-4xl font-black text-green-500">{{ resumo.finalizados }}</span>
        </div>

        <div class="bg-gradient-to-br from-[#1A1A1A] to-[#2D4483] p-6 rounded-3xl shadow-lg border border-black/5 flex flex-col justify-between text-white hover:-translate-y-1 transition-transform relative overflow-hidden">
          <div class="absolute -right-4 -top-4 w-16 h-16 bg-white/10 rounded-full blur-md"></div>
          <p class="text-[10px] font-black uppercase tracking-widest text-[#C2F2D9] mb-2 relative z-10">Faturamento Hoje</p>
          <span class="text-3xl font-black relative z-10">R$ {{ resumo.faturamento.toFixed(2) }}</span>
        </div>
      </div>

      <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        
        <!-- FILA DE PEDIDOS -->
        <div class="xl:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-xl border border-black/5">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-black italic uppercase tracking-tighter">Gerenciar Pedidos</h2>
            <div class="flex gap-2 bg-gray-100 p-1 rounded-xl">
              <button @click="filtroAtivo = 'todos'" :class="filtroAtivo === 'todos' ? 'bg-white shadow-sm text-[#1A1A1A]' : 'text-gray-400'" class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">Todos</button>
              <button @click="filtroAtivo = 'pendente'" :class="filtroAtivo === 'pendente' ? 'bg-white shadow-sm text-[#1A1A1A]' : 'text-gray-400'" class="px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all">Pendentes</button>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="border-b border-gray-100">
                  <th class="py-4 px-2 text-[10px] font-black uppercase tracking-widest text-gray-400">ID</th>
                  <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Cliente</th>
                  <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest text-gray-400 w-1/3">Resumo do Pedido</th>
                  <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Status</th>
                  <th class="py-4 px-4 text-[10px] font-black uppercase tracking-widest text-gray-400">Total</th>
                  <th class="py-4 px-2 text-[10px] font-black uppercase tracking-widest text-gray-400 text-right">Ação</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-50">
                <tr v-if="pedidosFiltrados.length === 0">
                  <td colspan="6" class="py-8 text-center text-sm font-bold text-gray-400 uppercase tracking-widest">Nenhum pedido encontrado.</td>
                </tr>
                
                <!-- ROW CLICÁVEL PARA ABRIR O MODAL -->
                <tr v-for="pedido in pedidosFiltrados" :key="pedido.id" @click="abrirModalPedido(pedido)" class="hover:bg-gray-50 transition-colors group cursor-pointer">
                  <td class="py-4 px-2 font-black text-sm">#{{ pedido.id }}</td>
                  <td class="py-4 px-4">
                    <p class="font-bold text-sm uppercase">{{ pedido.cliente }}</p>
                    <p class="text-[10px] text-gray-500 font-medium">{{ pedido.itensCount }} itens</p>
                  </td>
                  <td class="py-4 px-4">
                    <p class="text-[10px] font-bold text-gray-500 uppercase leading-tight line-clamp-2">{{ pedido.descricao_curta }}</p>
                  </td>
                  <td class="py-4 px-4">
                    <span :class="obterCorStatus(pedido.status)" class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest">
                      {{ pedido.status }}
                    </span>
                  </td>
                  <td class="py-4 px-4 font-black text-[#1A1A1A]">R$ {{ pedido.total.toFixed(2) }}</td>
                  <td class="py-4 px-2 text-right">
                    <button v-if="pedido.status === 'Pendente'" @click.stop="atualizarStatus(pedido, 'Preparo')" class="bg-[#1A1A1A] text-[#C2F2D9] px-4 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-black transition-colors shadow-md">
                      Aceitar
                    </button>
                    <button v-else-if="pedido.status === 'Preparo'" @click.stop="atualizarStatus(pedido, 'Saiu p/ Entrega')" class="bg-[#2D4483] text-white px-4 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest hover:bg-blue-900 transition-colors shadow-md">
                      Despachar
                    </button>
                    <span v-else class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-[#2D4483]">Ver Detalhes ➔</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- COLUNA DA DIREITA -->
        <div class="space-y-8">
          
          <!-- NOTIFICAÇÕES -->
          <div class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-black/5">
            <h2 class="text-lg font-black italic uppercase tracking-tighter mb-6">Avisos Recentes</h2>
            <div class="space-y-4">
              <div v-for="(notif, idx) in notificacoes" :key="idx" class="flex gap-4 items-start p-4 bg-gray-50 rounded-2xl border border-black/5">
                <span class="text-2xl">{{ notif.icone }}</span>
                <div>
                  <p class="font-bold text-xs uppercase text-[#1A1A1A] leading-tight">{{ notif.titulo }}</p>
                  <p class="text-[10px] text-gray-500 font-medium mt-1">{{ notif.tempo }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- ÁREA DOS ENTREGADORES ATIVOS (COM O NOVO FUNDO GRADIENTE) -->
          <div class="bg-gradient-to-br from-[#1A1A1A] to-[#2D4483] rounded-[2.5rem] p-8 shadow-xl text-white relative overflow-hidden flex flex-col justify-center items-center text-center">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-cover"></div>
            
            <h2 class="text-lg font-black italic uppercase tracking-tighter mb-4 relative z-10 text-[#C2F2D9]">Entregadores</h2>
            
            <div class="relative z-10 flex flex-col items-center gap-1">
              <span class="text-5xl mb-2 drop-shadow-md animate-bounce">🛵</span>
              <span class="text-3xl font-black italic tracking-tighter">2</span>
              <span class="text-[10px] font-black uppercase tracking-widest text-gray-300">Ativos no Momento</span>
            </div>
          </div>

        </div>

      </div>
    </main>

    <!-- MODAL DE DETALHES DO PEDIDO -->
    <div v-if="pedidoSelecionado" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      
      <div class="relative bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-[2.5rem] p-8 shadow-2xl animate-in zoom-in duration-300 custom-scrollbar z-50">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <!-- Cabeçalho do Modal -->
        <div class="border-b border-gray-100 pb-6 mb-6">
          <div class="flex items-center gap-4 mb-2">
            <h2 class="text-3xl font-black italic tracking-tighter uppercase leading-tight text-[#1A1A1A]">Pedido #{{ pedidoSelecionado.id }}</h2>
            <span :class="obterCorStatus(pedidoSelecionado.status)" class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest">
              {{ pedidoSelecionado.status }}
            </span>
          </div>
          <p class="text-xs text-gray-500 font-bold uppercase tracking-widest">Recebido às {{ pedidoSelecionado.hora }}</p>
        </div>

        <!-- Endereço de Entrega (Agora com Bairro) -->
        <div class="bg-[#F8FAFC] p-6 rounded-3xl border border-gray-100 mb-6 flex items-start gap-4">
          <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-2xl flex-shrink-0">📍</div>
          <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-[#2D4483] mb-1">Local de Entrega</p>
            <p class="font-bold text-sm uppercase text-[#1A1A1A]">{{ pedidoSelecionado.cliente }}</p>
            <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ pedidoSelecionado.endereco.rua }}, {{ pedidoSelecionado.endereco.numero }}</p>
            <p class="text-xs text-gray-600 font-bold">Bairro: {{ pedidoSelecionado.endereco.bairro }}</p>
            <p class="text-[10px] text-gray-400 mt-1">CEP: {{ pedidoSelecionado.endereco.cep }} • {{ pedidoSelecionado.endereco.cidade }}</p>
          </div>
        </div>

        <!-- Lista de Itens -->
        <div class="mb-8">
          <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-4">Itens do Pedido ({{ pedidoSelecionado.itensCount }})</h3>
          
          <div class="space-y-3">
            <div v-for="(item, idx) in pedidoSelecionado.lista_itens" :key="idx" class="flex justify-between items-center border-b border-gray-50 pb-3 last:border-0 last:pb-0 gap-3">
              <div class="flex items-center gap-3 flex-1">
                <span class="bg-gray-100 text-[#1A1A1A] w-8 h-8 flex items-center justify-center rounded-lg font-black text-xs flex-shrink-0">{{ item.qtd }}x</span>
                <p class="font-bold text-xs uppercase leading-tight">{{ item.nome }}</p>
              </div>
              <span class="font-black text-[#1A1A1A] text-sm flex-shrink-0">R$ {{ (item.preco * item.qtd).toFixed(2) }}</span>
            </div>
          </div>
        </div>

        <!-- Resumo Financeiro -->
        <div class="bg-gray-50 rounded-3xl p-6 border border-black/5 mb-8 flex justify-between items-center">
          <div class="space-y-1">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total a Receber</h3>
            <p class="font-bold text-xs text-gray-600">Forma de Pagamento: {{ pedidoSelecionado.pagamento }}</p>
          </div>
          <span class="font-black text-2xl text-[#1A1A1A]">R$ {{ pedidoSelecionado.total.toFixed(2) }}</span>
        </div>

        <!-- Botões de Ação do Modal -->
        <div class="flex flex-col sm:flex-row gap-4">
          <button @click="fecharModal" class="flex-1 py-4 bg-gray-100 text-[#1A1A1A] rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-gray-200 transition-all">
            Voltar
          </button>
          
          <button v-if="pedidoSelecionado.status === 'Pendente'" @click="atualizarStatus(pedidoSelecionado, 'Preparo'); fecharModal()" class="flex-1 py-4 bg-[#1A1A1A] text-[#C2F2D9] rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-xl">
            Aceitar Pedido
          </button>
          
          <button v-else-if="pedidoSelecionado.status === 'Preparo'" @click="atualizarStatus(pedidoSelecionado, 'Saiu p/ Entrega'); fecharModal()" class="flex-1 py-4 bg-[#2D4483] text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-900 transition-all shadow-xl">
            Despachar p/ Entregador
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

const router = useRouter()

// Variáveis de Controle
const filtroAtivo = ref('todos')
const pedidoSelecionado = ref(null)

// === DADOS MOCKADOS (Com detalhes completos e endereço com Bairro) ===

const resumo = ref({ pendentes: 3, emPreparo: 5, emEntrega: 2, finalizados: 18, faturamento: 845.50 })

const notificacoes = ref([
  { icone: '🚨', titulo: 'Novo pedido recebido (#1042)', tempo: 'Agora mesmo' },
  { icone: '✅', titulo: 'Pedido #1030 entregue', tempo: 'Há 15 min' },
  { icone: '⚠️', titulo: 'Entregador Carlos aguardando', tempo: 'Há 20 min' }
])

const pedidos = ref([
  { 
    id: '1042', cliente: 'Cauã Brenno', hora: '14:30',
    endereco: { rua: 'Av. Padre Cícero', numero: '1234', bairro: 'Salesianos', cep: '63010-000', cidade: 'Juazeiro do Norte - CE' },
    lista_itens: [ { nome: 'Queijo Coalho Tradicional Deleite', qtd: 1, preco: 30.90 }, { nome: 'Manteiga da Terra (Pote)', qtd: 2, preco: 7.50 } ],
    descricao_curta: '1x Queijo Coalho, 2x Manteiga da Terra', itensCount: 3, status: 'Pendente', pagamento: 'Pix', total: 45.90 
  },
  { 
    id: '1041', cliente: 'Maria Silva', hora: '14:25',
    endereco: { rua: 'Rua São Pedro', numero: '88', bairro: 'Centro', cep: '63010-150', cidade: 'Juazeiro do Norte - CE' },
    lista_itens: [ { nome: 'Iogurte Natural 1L', qtd: 1, preco: 22.50 } ],
    descricao_curta: '1x Iogurte Natural 1L', itensCount: 1, status: 'Pendente', pagamento: 'Cartão de Crédito', total: 22.50 
  },
  { 
    id: '1040', cliente: 'João Pedro', hora: '14:10',
    endereco: { rua: 'Rua do Cruzeiro', numero: '500', bairro: 'Socorro', cep: '63010-200', cidade: 'Juazeiro do Norte - CE' },
    lista_itens: [ { nome: 'Pão de Queijo Congelado', qtd: 2, preco: 25.00 }, { nome: 'Doce de Leite Artesanal', qtd: 3, preco: 20.66 } ],
    descricao_curta: '2x Pão de Queijo, 3x Doce de Leite', itensCount: 5, status: 'Preparo', pagamento: 'Pix', total: 112.00 
  },
  { 
    id: '1039', cliente: 'Ana Clara', hora: '13:50',
    endereco: { rua: 'Av. Castelo Branco', numero: '910', bairro: 'Pirajá', cep: '63030-000', cidade: 'Juazeiro do Norte - CE' },
    lista_itens: [ { nome: 'Queijo Mussarela Fatiado', qtd: 2, preco: 17.00 } ],
    descricao_curta: '2x Queijo Mussarela Fatiado', itensCount: 2, status: 'Saiu p/ Entrega', pagamento: 'Pix', total: 34.00 
  }
])

// === LÓGICA DE INTERFACE ===

const pedidosFiltrados = computed(() => {
  if (filtroAtivo.value === 'pendente') {
    return pedidos.value.filter(p => p.status === 'Pendente')
  }
  return pedidos.value
})

const obterCorStatus = (status) => {
  switch (status) {
    case 'Pendente': return 'bg-red-50 text-red-600 border border-red-100'
    case 'Preparo': return 'bg-yellow-50 text-yellow-600 border border-yellow-100'
    case 'Saiu p/ Entrega': return 'bg-blue-50 text-blue-600 border border-blue-100'
    case 'Entregue': return 'bg-green-50 text-green-600 border border-green-100'
    default: return 'bg-gray-100 text-gray-600'
  }
}

// === FUNÇÕES DO MODAL ===

const abrirModalPedido = (pedido) => {
  pedidoSelecionado.value = pedido
}

const fecharModal = () => {
  pedidoSelecionado.value = null
}

const atualizarStatus = (pedido, novoStatus) => {
  if(confirm(`Mudar o status do pedido #${pedido.id} para "${novoStatus}"?`)) {
    pedido.status = novoStatus
    
    // Atualiza KPIs da dashboard
    if (novoStatus === 'Preparo') {
      resumo.value.pendentes--
      resumo.value.emPreparo++
    } else if (novoStatus === 'Saiu p/ Entrega') {
      resumo.value.emPreparo--
      resumo.value.emEntrega++
    }
  }
}

const fazerLogout = () => {
  if(confirm("Deseja sair do painel do lojista?")) {
    localStorage.removeItem('token')
    router.push('/')
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
</style>