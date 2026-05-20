<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] text-[#1A1A1A] font-sans pb-12">
    
    <!-- NAVBAR DO LOJISTA -->
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
              <button @click="alternarStatusLojista" :class="lojaAberta ? 'bg-[#C2F2D9] text-[#1A1A1A]' : 'bg-red-100 text-red-600'" class="flex items-center gap-2 px-5 py-2.5 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all shadow-sm border border-black/5">
                <Zap :size="14" :class="lojaAberta ? 'fill-[#1A1A1A]' : 'fill-none'" />
                {{ lojaAberta ? 'Loja Aberta' : 'Loja Fechada' }}
              </button>
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
                    <p class="text-[10px] text-gray-500 font-bold uppercase flex items-center gap-1">
                      <MapPin :size="12" /> {{ formatarEndereco(pedido.endereco) }}
                    </p>
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
                    <button 
                      v-if="pedido.status === 'Pendente'"
                      @click="atualizarStatus(pedido, 'Em Preparo')"
                      class="bg-[#1A1A1A] text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-black hover:scale-105 transition-all shadow-md tracking-widest flex items-center gap-2"
                    >
                      Aceitar <ArrowRight :size="12" />
                    </button>
                    <button 
                      v-else-if="pedido.status === 'Em Preparo'"
                      @click="atualizarStatus(pedido, 'Despachado')"
                      class="bg-[#2D4483] text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-blue-900 hover:scale-105 transition-all shadow-md tracking-widest flex items-center gap-2"
                    >
                      Despachar <ArrowRight :size="12" />
                    </button>
                    <button 
                      v-else-if="verificarSePedidoEstaDespachadoEAguardandoFinalizacao(pedido)"
                      @click="atualizarStatus(pedido, 'Entregue')"
                      class="bg-green-600 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase hover:bg-green-700 hover:scale-105 transition-all shadow-md tracking-widest flex items-center gap-2"
                    >
                      Confirmar Entrega <ArrowRight :size="12" />
                    </button>
                    <span v-else class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-[#2D4483] cursor-pointer" @click="abrirModalPedido(pedido)">
                      Ver Detalhes ➔
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- COLUNA DA DIREITA -->
        <div class="space-y-8">
          

          <!-- ÁREA DOS ENTREGADORES ATIVOS -->
          <div class="bg-gradient-to-br from-[#1A1A1A] to-[#2D4483] rounded-[2.5rem] p-8 shadow-xl text-white relative overflow-hidden flex flex-col justify-center items-center text-center">
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-cover"></div>
            
            <h2 class="text-lg font-black italic uppercase tracking-tighter mb-4 relative z-10 text-[#C2F2D9]">Entregadores</h2>
            
            <div class="relative z-10 flex flex-col items-center gap-1">
              <div class="flex items-center gap-3 mt-4">
                <Bike :size="20" :class="entregadoresOnline > 0 ? 'text-[#C2F2D9]' : 'text-gray-400'" />
                <p class="text-xs font-bold uppercase" :class="entregadoresOnline > 0 ? 'text-white' : 'text-gray-500'">
                  {{ entregadoresOnline }} {{ entregadoresOnline === 1 ? 'Entregador ativo' : 'Entregadores ativos' }}
                </p>
              </div>
              <span class="text-[10px] font-black uppercase tracking-widest text-gray-300">No momento</span>
            </div>
          </div>

        </div>

      </div>
    </main>

    <!-- MODAL DE DETALHES DO PEDIDO -->
    <div v-if="pedidoSelecionado" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      
      <div class="relative bg-white w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-t-[2.5rem] md:rounded-[2.5rem] p-6 lg:p-10 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300 custom-scrollbar z-50">
        <button @click="pedidoSelecionado = null" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200 flex items-center justify-center">
          <X :size="20" />
        </button>
        
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
          <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-2xl flex-shrink-0"><MapPin :size="24" /></div>
          <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-[#2D4483] mb-1">Local de Entrega</p>
            <p class="font-bold text-sm uppercase text-[#1A1A1A]">{{ pedidoSelecionado.cliente }}</p>
            <p class="text-xs text-gray-600 mt-1 leading-relaxed">{{ pedidoSelecionado.endereco.rua }}, {{ pedidoSelecionado.endereco.numero }}</p>
            <p class="text-xs text-gray-600 font-bold">Bairro: {{ pedidoSelecionado.endereco.bairro || 'Não Informado' }}</p>
            <p class="text-[10px] text-gray-400 mt-1">CEP: {{ pedidoSelecionado.endereco.cep || 'Não Informado' }} • {{ pedidoSelecionado.endereco.cidade }}</p>
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
          
          <button v-if="pedidoSelecionado.status === 'Pendente'" @click="atualizarStatus(pedidoSelecionado, 'Em Preparo'); fecharModal()" class="flex-1 py-4 bg-[#1A1A1A] text-[#C2F2D9] rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-xl">
            Aceitar Pedido
          </button>
          
          <button v-else-if="pedidoSelecionado.status === 'Em Preparo'" @click="atualizarStatus(pedidoSelecionado, 'Despachado'); fecharModal()" class="flex-1 py-4 bg-[#2D4483] text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-blue-900 transition-all shadow-xl">
            Despachar p/ Entregador
          </button>

          <button v-else-if="verificarSePedidoEstaDespachadoEAguardandoFinalizacao(pedidoSelecionado)" @click="atualizarStatus(pedidoSelecionado, 'Entregue'); fecharModal()" class="flex-1 py-4 bg-green-600 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-green-700 transition-all shadow-xl">
            Confirmar Entrega
          </button>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import iRota from '../assets/iRota.png'
import { ref, reactive, onMounted, onUnmounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '../services/api'
import { 
  Zap, 
  ArrowRight, 
  Bike, 
  X, 
  MapPin 
} from 'lucide-vue-next'

const roteador = useRouter()
const rotaAtual = useRoute()

// Variáveis de Controle
const filtroAtivo = ref('todos')
const pedidoSelecionado = ref(null)
const lojaAberta = ref(true)
const entregadoresOnline = ref(0)

// Os pedidos reais vão entrar aqui
const pedidos = ref([])

// Resumo calculado em tempo real
const resumo = ref({ pendentes: 0, emPreparo: 0, emEntrega: 0, finalizados: 0, faturamento: 0 })

const notificacoes = ref([])

// === LÓGICA DE INTERFACE ===

const pedidosFiltrados = computed(() => {
  return pedidos.value
})

const formatarEndereco = (endereco) => {
  if (!endereco) return 'Endereço não informado';
  if (typeof endereco === 'object') {
    return `${endereco.rua}, ${endereco.numero}${endereco.bairro ? ' - ' + endereco.bairro : ''}`;
  }
  return endereco;
}

const obterCorStatus = (status) => {
  const statusMinusculo = status ? String(status).toLowerCase() : '';
  if (statusMinusculo.includes('pendente')) {
    return 'bg-red-50 text-red-600 border border-red-100';
  }
  if (statusMinusculo.includes('preparo') || statusMinusculo.includes('preparando')) {
    return 'bg-yellow-50 text-yellow-600 border border-yellow-100';
  }
  if (
    statusMinusculo.includes('saiu') ||
    statusMinusculo.includes('aguardando') ||
    statusMinusculo.includes('despachado') ||
    statusMinusculo.includes('perto') ||
    statusMinusculo.includes('caminho')
  ) {
    return 'bg-blue-50 text-blue-600 border border-blue-100';
  }
  if (statusMinusculo.includes('entregue') || statusMinusculo.includes('concluido')) {
    return 'bg-green-50 text-green-600 border border-green-100';
  }
  if (statusMinusculo.includes('cancelado')) {
    return 'bg-gray-100 text-gray-500 border border-gray-300';
  }
  return 'bg-gray-100 text-gray-600';
}

// === INTEGRAÇÃO COM A API (A Mágica!) ===

// Tradutor para garantir que o HTML mostre os botões
const formatarStatusParaHTML = (statusBanco) => {
  if (!statusBanco) {
    return 'Pendente';
  }
  const statusMinusculo = String(statusBanco).toLowerCase();
  
  if (statusMinusculo.includes('pendente')) {
    return 'Pendente';
  }
  if (statusMinusculo.includes('preparo') || statusMinusculo.includes('preparando')) {
    return 'Em Preparo';
  }
  if (statusMinusculo.includes('saiu') || statusMinusculo.includes('aguardando')) {
    return 'Saiu p/ Entrega';
  }
  if (statusMinusculo.includes('entregue') || statusMinusculo.includes('concluido')) {
    return 'Entregue';
  }
  
  return statusBanco.charAt(0).toUpperCase() + statusBanco.slice(1);
}

const verificarSePedidoEstaDespachadoEAguardandoFinalizacao = (pedido) => {
  if (!pedido || !pedido.status) {
    return false;
  }
  const statusFormatado = pedido.status;
  const statusesNaoFinalizadosOuNaoDespachados = [
    'Pendente',
    'Em Preparo',
    'Entregue',
    'Cancelado',
    'Finalizado',
    'Concluido',
    'Concluído'
  ];
  return !statusesNaoFinalizadosOuNaoDespachados.includes(statusFormatado);
}

const buscarPedidos = async () => {
  try {
    const resposta = await api.get('/lojista/pedidos');
    
    pedidos.value = resposta.data.map(pedidoDoBanco => {
      
      // ✨ DESEMPACOTANDO O JSON COM SEGURANÇA
      let enderecoLido = { rua: 'Não informado', numero: '', bairro: '', cep: '' };
      
      if (pedidoDoBanco.endereco_entrega) {
        try {
          const enderecoParcial = JSON.parse(pedidoDoBanco.endereco_entrega);
          if (enderecoParcial && typeof enderecoParcial === 'object') {
            // Se conseguiu ler o JSON, mescla os dados
            enderecoLido = { ...enderecoLido, ...enderecoParcial };
          } else {
            // Se por acaso vier só uma string normal
            enderecoLido.rua = pedidoDoBanco.endereco_entrega;
          }
        } catch (erroDeLeitura) {
          // Se for um pedido antigo que falhar no JSON.parse
          enderecoLido.rua = pedidoDoBanco.endereco_entrega;
        }
      }

      const listaItens = pedidoDoBanco.produtos ? pedidoDoBanco.produtos.map(produto => ({
        nome: produto.nome,
        qtd: produto.pivot.quantidade,
        preco: parseFloat(produto.pivot.preco_unitario)
      })) : [];

      const contagemItens = pedidoDoBanco.produtos ? pedidoDoBanco.produtos.reduce((acumulador, itemAtual) => acumulador + itemAtual.pivot.quantidade, 0) : 0;

      return {
        ...pedidoDoBanco,
        id: pedidoDoBanco.id,
        cliente: pedidoDoBanco.nome_cliente || 'Cliente #' + pedidoDoBanco.user_id, 
        hora: new Date(pedidoDoBanco.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
        
        // Passamos o objeto perfeito que acabamos de montar ali em cima!
        endereco: enderecoLido,
        
        lista_itens: listaItens,
        descricao_curta: pedidoDoBanco.descricao,
        itensCount: contagemItens,
        status: formatarStatusParaHTML(pedidoDoBanco.status), 
        pagamento: pedidoDoBanco.forma_pagamento || 'Não informado',
        total: parseFloat(pedidoDoBanco.valor_total)
      };
    });

    let pendentes = 0;
    let emPreparo = 0;
    let emEntrega = 0;
    let finalizados = 0;
    let faturamento = 0;
    
    pedidos.value.forEach(pedido => {
      const statusMinusculo = pedido.status.toLowerCase();
      if (statusMinusculo.includes('pendente')) {
        pendentes++;
      } else if (statusMinusculo.includes('preparo')) {
        emPreparo++;
      } else if (
        statusMinusculo.includes('saiu') ||
        statusMinusculo.includes('aguardando') ||
        statusMinusculo.includes('despachado') ||
        statusMinusculo.includes('perto') ||
        statusMinusculo.includes('caminho')
      ) {
        emEntrega++;
      } else if (statusMinusculo.includes('entregue') || statusMinusculo.includes('concluido')) {
        finalizados++;
        faturamento += pedido.total;
      }
    });
    
    resumo.value = { pendentes, emPreparo, emEntrega, finalizados, faturamento };

  } catch (erroDeBusca) {
    console.error("Erro ao buscar pedidos:", erroDeBusca);
  }
}
const identificadorDoIntervaloDeAtualizacao = ref(null)

onMounted(() => {
  buscarPedidos()
  buscarStatusLoja()
  buscarEntregadoresOnline()
  
  identificadorDoIntervaloDeAtualizacao.value = setInterval(() => {
    buscarPedidos()
    buscarEntregadoresOnline()
  }, 10000)
})

onUnmounted(() => {
  if (identificadorDoIntervaloDeAtualizacao.value) {
    clearInterval(identificadorDoIntervaloDeAtualizacao.value)
  }
})

const buscarStatusLoja = async () => {
  try {
    const res = await api.get('/lojista/perfil')
    if (res.data) {
      lojaAberta.value = res.data.aberto
    }
  } catch (error) {}
}

const alternarStatusLojista = async () => {
  const novoStatus = !lojaAberta.value
  try {
    await api.post('/lojista/perfil', { aberto: novoStatus })
    lojaAberta.value = novoStatus
  } catch (error) {
    alert("Erro ao mudar o status da loja.")
  }
}

const buscarEntregadoresOnline = async () => {
  try {
    const res = await api.get('/lojista/entregadores-online')
    entregadoresOnline.value = res.data.count || 0
  } catch (error) {}
}

// === FUNÇÕES DO MODAL E AÇÕES ===

const abrirModalPedido = (pedido) => {
  pedidoSelecionado.value = pedido
}

const fecharModal = () => {
  pedidoSelecionado.value = null
}

const atualizarStatus = async (pedido, novoStatus) => {
  if(confirm(`Mudar o status do pedido #${pedido.id} para "${novoStatus}"?`)) {
    try {
      await api.put(`/lojista/pedidos/${pedido.id}/status`, {
        status: novoStatus
      })
      
      alert('Status atualizado com sucesso!')
      fecharModal()
      buscarPedidos() // Chama a API para trazer os dados novos na mesma hora
    } catch (error) {
      console.error("Erro ao atualizar o status no banco:", error)
      alert("Erro ao tentar atualizar o pedido.")
    }
  }
}

const fazerLogout = () => {
  if(confirm("Deseja sair do painel do lojista?")) {
    localStorage.removeItem('token')
    localStorage.removeItem('tipoUsuario')
    roteador.push('/')
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