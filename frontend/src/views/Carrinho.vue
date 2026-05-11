<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F1F7] pb-32 md:pb-20 text-[#1A1A1A] relative overflow-x-hidden">
    
<nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-6 md:px-8">
  <div class="flex items-center gap-3">
    <img :src="iRota" alt="Rota Certa" class="w-10 h-10 object-contain drop-shadow-sm">
    <h1 class="text-2xl font-black italic uppercase tracking-tighter">Carrinho</h1>
  </div>
  
  <div class="hidden md:flex gap-6 items-center">
    <router-link title="Início" to="/home" class="text-2xl hover:scale-110 transition-all opacity-100">🏠</router-link>
    <router-link title="Meus Pedidos" to="/meus-pedidos" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">📄</router-link>
    <router-link title="Meu Perfil" to="/perfil" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">👤</router-link>
    <button class="relative text-2xl hover:scale-110 transition-all opacity-100">
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

      <router-link to="/meus-pedidos" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">📄</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Pedidos</span>
      </router-link>

      <router-link to="/carrinho" class="flex flex-col items-center gap-1 text-[#C2F2D9] relative">
        <div class="bg-[#C2F2D9]/20 p-2 rounded-xl transition-all relative">
          <span class="text-2xl">🛒</span>
          <span v-if="totalItensCarrinho > 0" class="absolute top-0 -right-1 bg-[#C2F2D9] text-[#1A1A1A] text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black shadow-md">
            {{ totalItensCarrinho }}
          </span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Cesta</span>
      </router-link>

      <router-link to="/perfil" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">👤</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Perfil</span>
      </router-link>
    </div>

    <main class="p-4 lg:p-10 max-w-4xl mx-auto space-y-6">
      
      <!-- Seção do Carrinho Vazio -->
      <section v-if="itensNoCarrinho.length === 0" class="bg-white rounded-3xl p-10 text-center shadow-xl border border-black/5 flex flex-col items-center gap-4">
        <span class="text-6xl opacity-40 grayscale">🛒</span>
        <h2 class="text-xl font-black uppercase italic tracking-tighter">Sua cesta está vazia!</h2>
        <p class="text-sm text-gray-500 font-medium">Volte para a loja e adicione produtos deliciosos.</p>
        <router-link to="/home" class="mt-4 bg-[#1A1A1A] text-white px-8 py-4 rounded-2xl font-black uppercase tracking-widest hover:bg-black transition-all text-xs">
          Ir para a Loja
        </router-link>
      </section>

      <!-- Seção de Itens -->
      <section v-else class="bg-white rounded-3xl p-5 md:p-8 shadow-xl border border-black/5">
        <h3 class="text-xs font-black uppercase tracking-widest opacity-40 mb-5 italic">Itens Selecionados</h3>
        
        <transition-group name="list" tag="div" class="space-y-5">
          <div v-for="(item, index) in itensNoCarrinho" :key="item.id" class="flex flex-col sm:flex-row items-center gap-4 pb-5 border-b border-gray-100 last:border-0 last:pb-0 group">
            
            <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center p-2 border border-black/5 shadow-sm">
              <img :src="item.foto" class="max-h-full max-w-full object-contain" />
            </div>
            
            <div class="flex-1 text-center sm:text-left">
              <p class="text-[9px] font-black text-[#2D4483] uppercase tracking-wider mb-0.5">{{ item.marca }}</p>
              <p class="font-bold text-xs uppercase leading-tight">{{ item.nome }}</p>
              <p class="text-[#1A1A1A] font-black mt-1.5 text-base">R$ {{ (item.preco * item.quantidade).toFixed(2) }}</p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
              <div class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-black/5">
                <button @click="item.quantidade > 1 ? alterarQuantidade(index, -1) : removerDoCarrinho(index)" class="w-9 h-9 flex items-center justify-center font-black bg-white rounded-lg shadow-sm hover:bg-gray-100">-</button>
                <span class="font-black text-base w-5 text-center">{{ item.quantidade }}</span>
                <button @click="alterarQuantidade(index, 1)" class="w-9 h-9 flex items-center justify-center font-black bg-[#1A1A1A] text-white rounded-lg shadow-sm hover:bg-black">+</button>
              </div>

              <button 
                @click="removerDoCarrinho(index)" 
                class="w-9 h-9 flex items-center justify-center bg-red-600 text-white rounded-lg shadow-md active:scale-90 transition-all border-none"
                title="Remover Item"
              >
                🗑️
              </button>
            </div>
          </div>
        </transition-group>
      </section>

      <!-- Local de Entrega -->
      <section v-if="itensNoCarrinho.length > 0" class="bg-white rounded-3xl p-5 md:p-8 shadow-xl border border-black/5 space-y-4">
        <div class="flex justify-between items-center text-xs font-black uppercase tracking-widest opacity-40 italic">
          <h3>Local de Entrega</h3>
          <button @click="abrirModal('enderecos')" class="text-[#2D4483] hover:underline font-bold">Trocar Local</button>
        </div>
        
        <div v-if="enderecoEntrega" class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border border-black/5">
          <span class="text-2xl">📍</span>
          <div>
            <p class="font-black text-xs uppercase tracking-tight">{{ enderecoEntrega.titulo }}</p>
            <p class="text-[10px] text-gray-500 font-medium">{{ enderecoEntrega.rua }}, {{ enderecoEntrega.numero }} - {{ enderecoEntrega.cidade }}</p>
          </div>
        </div>
        <div v-else class="p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100 text-sm font-bold flex items-center gap-2">
          ⚠️ Por favor, adicione ou selecione um endereço de entrega.
        </div>
      </section>

      <!-- Forma de Pagamento -->
      <section v-if="itensNoCarrinho.length > 0" class="bg-white rounded-3xl p-5 md:p-8 shadow-xl border border-black/5 space-y-5">
        <h3 class="text-xs font-black uppercase tracking-widest opacity-40 italic">Forma de Pagamento</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <button 
            v-for="metodo in metodos" :key="metodo.id" @click="metodoPago = metodo.id"
            :class="metodoPago === metodo.id ? 'bg-[#1A1A1A] text-white shadow-lg' : 'bg-gray-50 text-[#1A1A1A] opacity-60'"
            class="p-5 rounded-2xl border border-black/5 flex flex-col items-center gap-2 transition-all duration-200"
          >
            <!-- Alterado temporariamente a imagem do PIX para Emoji para não quebrar sem a imagem -->
              <img v-if="metodo.id === 'pix'" :src="imgPix" class="w-8 h-8 object-contain" :class="metodoPago === 'pix' ? 'invert' : 'grayscale opacity-50'" />            <span v-else class="text-2xl">{{ metodo.icon }}</span>
            <span class="font-black text-[9px] uppercase tracking-widest">{{ metodo.nome }}</span>
          </button>
        </div>
      </section>

      <!-- Resumo Final -->
      <section v-if="itensNoCarrinho.length > 0" class="bg-[#1A1A1A] text-white rounded-[2rem] p-8 shadow-2xl space-y-5 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#C2F2D9] rounded-full opacity-5"></div>

        <div class="border-b border-white/10 pb-5 space-y-3 relative z-10">
          <div class="flex justify-between items-center text-[10px] font-bold uppercase text-white/40 tracking-wider">
            <span>Subtotal</span>
            <span>R$ {{ subtotal }}</span>
          </div>
          <div v-if="desconto > 0" class="flex justify-between items-center text-[10px] font-bold uppercase text-red-500 tracking-wider">
            <span>Descontos Aplicados</span>
            <span>- R$ {{ desconto.toFixed(2) }}</span>
          </div>
          <div class="flex justify-between items-center text-[10px] font-bold uppercase text-[#C2F2D9] tracking-wider">
            <span>Frete Fixo</span>
            <span>R$ {{ frete.toFixed(2) }}</span>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-end gap-6 relative z-10">
          <div class="text-center sm:text-left flex-1">
            <p class="text-white/40 font-black text-[9px] uppercase tracking-[0.4em] mb-0.5">Total a Pagar</p>
            <p class="text-5xl font-black italic tracking-tighter text-[#C2F2D9] mb-1">R$ {{ totalFinal }}</p>
            <p class="text-xs text-[#C2F2D9] opacity-80 font-bold flex items-center gap-1 justify-center sm:justify-start">
              ⭐ Você ganhará <span class="text-white">{{ pontosGanhos }} pts</span> nesta compra
            </p>
          </div>
          <button @click="finalizarCompra" :disabled="carregandoPedido" class="w-full sm:w-auto px-10 py-5 bg-[#C2F2D9] text-[#1A1A1A] font-black text-base rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl uppercase tracking-widest disabled:opacity-50">
            {{ carregandoPedido ? 'Processando...' : 'Finalizar Compra' }}
          </button>
        </div>
      </section>

    </main>

    <!-- Modal de Endereços -->
    <div v-if="modalAtivo === 'enderecos'" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300 max-h-[90vh] overflow-y-auto custom-scrollbar">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <div class="mb-8">
          <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-1">Endereços</h2>
          <p class="text-gray-500 text-xs font-medium">Selecione onde vamos entregar suas compras</p>
        </div>

        <div v-if="!mostrandoForm" class="space-y-4">
          <div 
            v-for="(end, index) in enderecosSalvos" :key="index" 
            @click="selecionarEndereco(end)"
            class="flex items-start gap-4 p-4 rounded-2xl border-2 transition-all cursor-pointer relative group"
            :class="enderecoEntrega?.id === end.id ? 'border-[#1A1A1A] bg-gray-50' : 'border-transparent bg-white shadow-sm hover:border-black/10 hover:shadow-md'"
          >
            <div class="text-2xl">📍</div>
            <div class="flex-1">
              <p class="font-black text-xs uppercase">{{ end.titulo }}</p>
              <p class="text-[11px] text-gray-500 mt-1">{{ end.rua }}, {{ end.numero }}</p>
              <p class="text-[11px] text-gray-500">{{ end.bairro }} - {{ end.cidade }}</p>
            </div>
            <div v-if="enderecoEntrega?.id === end.id" class="w-6 h-6 bg-[#1A1A1A] text-[#C2F2D9] rounded-full flex items-center justify-center text-xs font-black">
              ✓
            </div>
          </div>

          <button @click="mostrandoForm = true" class="w-full py-5 border-2 border-dashed border-gray-200 rounded-2xl text-gray-400 font-black uppercase tracking-widest text-xs hover:border-[#1A1A1A] hover:text-[#1A1A1A] transition-all">
            + Adicionar Novo Endereço
          </button>
        </div>

        <form v-if="mostrandoForm" @submit.prevent="salvarEndereco" class="space-y-4">
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Nome do Local</label>
            <input v-model="novoEndereco.titulo" type="text" placeholder="Ex: Casa da Mãe" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">CEP</label>
              <input v-model="novoEndereco.cep" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
            </div>
            <div>
              <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Número</label>
              <input v-model="novoEndereco.numero" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
            </div>
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Rua / Avenida</label>
            <input v-model="novoEndereco.rua" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Bairro</label>
            <input v-model="novoEndereco.bairro" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <button type="button" @click="mostrandoForm = false" class="py-4 font-black uppercase text-[10px] tracking-widest text-gray-400 hover:text-[#1A1A1A]">Cancelar</button>
            <button type="submit" class="bg-[#1A1A1A] text-[#C2F2D9] py-4 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-black">Salvar</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import iRota from '../assets/iRota.png'
import imgPix from '../assets/pix.png' 
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api' // Usando nossa conexão protegida

const router = useRouter()

// === ESTADO DO CARRINHO (LocalStorage) ===
const itensNoCarrinho = ref(JSON.parse(localStorage.getItem('carrinho') || '[]'))

const totalItensCarrinho = computed(() => {
  if (!Array.isArray(itensNoCarrinho.value)) return 0
  return itensNoCarrinho.value.reduce((total, item) => total + item.quantidade, 0)
})

// === CONTROLE DO MODAL ===
const modalAtivo = ref(null)
const mostrandoForm = ref(false)

// === DADOS DE RESUMO E PAGAMENTO ===
const metodoPago = ref('pix')
const frete = ref(7.00)
const desconto = ref(0.00) // Pode implementar cupons depois!
const carregandoPedido = ref(false)

const metodos = [
  { id: 'pix', nome: 'Pix', icon: '💠' },
  { id: 'cartao', nome: 'Cartão', icon: '💳' },
  { id: 'dinheiro', nome: 'Dinheiro', icon: '💵' }
]

// === CÁLCULOS MATEMÁTICOS ===
const subtotal = computed(() => {
  return itensNoCarrinho.value.reduce((acc, item) => acc + (item.preco * item.quantidade), 0).toFixed(2)
})

const totalFinal = computed(() => {
  const v = (parseFloat(subtotal.value) + frete.value - desconto.value)
  return v.toFixed(2)
})

// A Mágica do Clube Rota Certa: R$ 1 = 1 Ponto (arredondado para baixo)
const pontosGanhos = computed(() => {
  return Math.floor(parseFloat(totalFinal.value))
})

// === FUNÇÕES DE MANIPULAÇÃO DO CARRINHO ===
const sincronizarLocalStorage = () => {
  localStorage.setItem('carrinho', JSON.stringify(itensNoCarrinho.value))
}

const alterarQuantidade = (index, delta) => {
  itensNoCarrinho.value[index].quantidade += delta
  sincronizarLocalStorage()
}

const removerDoCarrinho = (index) => {
  if (confirm("Remover este produto da cesta?")) {
    itensNoCarrinho.value.splice(index, 1)
    sincronizarLocalStorage()
  }
}

// === GESTÃO DE ENDEREÇOS (API) ===
const enderecosSalvos = ref([])
const novoEndereco = ref({ titulo: '', cep: '', numero: '', rua: '' })
const enderecoEntrega = ref(null)

const buscarEnderecos = async () => {
  try {
    const res = await api.get('/enderecos')
    if (res.data.length > 0) {
      enderecosSalvos.value = res.data.map(end => ({
        ...end,
        titulo: end.nome_local,
        bairro: end.bairro || 'Sem Bairro',
        cidade: end.cidade || 'Sem Cidade'
      }))
      // Pré-seleciona o primeiro endereço
      enderecoEntrega.value = enderecosSalvos.value[0]
    }
  } catch (error) {
    console.error("Erro ao buscar endereços no carrinho:", error)
  }
}

onMounted(() => {
  buscarEnderecos()
})

const abrirModal = (tipo) => { modalAtivo.value = tipo; mostrandoForm.value = false }
const fecharModal = () => { modalAtivo.value = null }

const selecionarEndereco = (end) => {
  enderecoEntrega.value = end
  fecharModal()
}

// === SALVAR ENDEREÇO ===
const salvarEndereco = async () => {
  try {
    const payload = {
      nome_local: novoEndereco.value.titulo, 
      cep: novoEndereco.value.cep,
      numero: novoEndereco.value.numero,
      rua: novoEndereco.value.rua,
      bairro: novoEndereco.value.bairro // ✨ Agora o bairro vai pro banco!
    }

    const response = await api.post('/enderecos', payload)
    const endBanco = response.data.endereco

    const endFormatado = {
      ...endBanco,
      titulo: endBanco.nome_local 
    }

    enderecosSalvos.value.push(endFormatado)
    enderecoEntrega.value = endFormatado 
    
    mostrandoForm.value = false
    fecharModal()
    // Limpando o form, agora incluindo o bairro
    novoEndereco.value = { titulo: '', cep: '', numero: '', rua: '', bairro: '' }
    
  } catch (error) {
    console.error("Erro ao salvar endereço:", error)
    alert("Não foi possível salvar o endereço.")
  }
}

// === FINALIZAR PEDIDO ===
const finalizarCompra = async () => {
  if (!enderecoEntrega.value) {
    return alert("⚠️ Por favor, selecione ou adicione um local de entrega!")
  }
  
  if (itensNoCarrinho.value.length === 0) {
    return alert("⚠️ Seu carrinho está vazio!")
  }

  carregandoPedido.value = true

  try {
    let lat = null;
    let lng = null;
    let cidadeReal = '';
    let estadoReal = '';
    
    // 1. ✨ INTEGRAÇÃO VIACEP: Descobre a cidade exata baseada no CEP do cliente!
    const cepLimpo = enderecoEntrega.value.cep.replace(/\D/g, '');
    try {
      if (cepLimpo.length === 8) {
        const viaCepRes = await fetch(`https://viacep.com.br/ws/${cepLimpo}/json/`);
        const viaCepData = await viaCepRes.json();
        if (!viaCepData.erro) {
          cidadeReal = viaCepData.localidade; // Pega a cidade real (ex: Trindade, Juazeiro...)
          estadoReal = viaCepData.uf; // Pega o Estado (PE, CE...)
        }
      }
    } catch (e) { console.warn("Aviso: ViaCEP falhou, usando dados locais."); }

    // 2. ✨ SATÉLITE BLINDADO: Manda a rua e a cidade exata pro Nominatim
    const rua = enderecoEntrega.value.rua;
    const numero = enderecoEntrega.value.numero;
    
    // Só adiciona a cidade na busca se o ViaCEP achou, para não confundir o satélite
    const enderecoBusca = cidadeReal 
      ? `${rua}, ${numero}, ${cidadeReal}, ${estadoReal}, Brasil`
      : `${rua}, ${numero}, Brasil`;
      
    console.log("🔍 Buscando no satélite:", enderecoBusca);
    
    try {
      const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(enderecoBusca)}`;
      const response = await fetch(url);
      const data = await response.json();
      
      if (data && data.length > 0) {
        lat = data[0].lat;
        lng = data[0].lon;
        console.log("📍 GPS cravado com sucesso!", lat, lng);
      } else {
        // Se o satélite não achar o NÚMERO da casa, tenta achar pelo menos a RUA
        const buscaSemNumero = cidadeReal ? `${rua}, ${cidadeReal}, Brasil` : `${rua}, Brasil`;
        const resSemNum = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(buscaSemNumero)}`);
        const dataSemNum = await resSemNum.json();
        if (dataSemNum && dataSemNum.length > 0) {
          lat = dataSemNum[0].lat;
          lng = dataSemNum[0].lon;
        }
      }
    } catch (e) {
      console.warn("Erro ao buscar coordenadas no mapa livre.");
    }

    // 3. Monta o pacote final pra mandar pro Laravel
    const enderecoCompleto = JSON.stringify({
      rua: rua,
      numero: numero,
      bairro: enderecoEntrega.value.bairro,
      cep: enderecoEntrega.value.cep,
      cidade: cidadeReal // Salvamos a cidade no recibo!
    })

    const payload = {
      endereco_entrega: enderecoCompleto, 
      forma_pagamento: metodoPago.value,
      valor_total: parseFloat(totalFinal.value),
      taxa_entrega: parseFloat(frete.value),
      pontos_ganhos: pontosGanhos.value,
      lojista_id: itensNoCarrinho.value[0]?.lojista_id || 1, 
      lat_entrega: lat, // O GPS EXATO vai pro banco aqui!
      lng_entrega: lng, // O GPS EXATO vai pro banco aqui!
      itens: itensNoCarrinho.value.map(item => ({
        id: item.id,                 
        produto_id: item.id,         
        quantidade: item.quantidade,
        preco_unitario: parseFloat(item.preco)
      }))
    }

    await api.post('/pedidos', payload)

    const pontosConquistados = pontosGanhos.value;
    itensNoCarrinho.value = []
    localStorage.removeItem('carrinho')
    
    alert(`🎉 Pedido realizado! Você ganhou ${pontosConquistados} pontos!`)
    router.push('/meus-pedidos')

  } catch (error) {
    console.error("Erro ao finalizar compra:", error)
    alert("Erro ao finalizar o pedido.")
  } finally {
    carregandoPedido.value = false
  }
}
</script>

<style scoped>
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
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}
</style>