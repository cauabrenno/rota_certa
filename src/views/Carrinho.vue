<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F1F7] pb-32 md:pb-20 text-[#1A1A1A] relative overflow-x-hidden">
    
  <nav class="bg-white/90 backdrop-blur-sm p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex items-center justify-between px-6 md:px-8">
      <div class="flex items-center gap-2">
        <router-link to="/home" class="text-2xl hover:scale-110 transition-all md:hidden mr-2">⬅️</router-link>
        <span class="text-3xl hidden md:block">⚡</span>
        <h1 class="text-2xl font-black italic uppercase tracking-tighter">Carrinho</h1>
      </div>
      
      <div class="hidden md:flex gap-6 items-center">
        <router-link title="Início" to="/home" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">🏠</router-link>
        <router-link title="Meus Pedidos" to="/meus-pedidos" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">📄</router-link>
        <router-link title="Meu Perfil" to="/perfil" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">👤</router-link>
        <button @click="irParaCarrinho" class="relative text-2xl hover:scale-110 transition-all opacity-100">
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

      <router-link to="/meus-pedidos" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">📄</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Pedidos</span>
      </router-link>

      <router-link to="/carrinho" class="flex flex-col items-center gap-1 text-[#C2F2D9] relative">
        <div class="bg-[#C2F2D9]/20 p-2 rounded-xl transition-all relative">
          <span class="text-2xl">🛒</span>
          <span class="absolute top-0 -right-1 bg-[#C2F2D9] text-[#1A1A1A] text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black shadow-md">3</span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Cesta</span>
      </router-link>

      <router-link to="/perfil" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">👤</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Perfil</span>
      </router-link>
    </div>

    <main class="p-4 lg:p-10 max-w-4xl mx-auto space-y-6">
      
      <section class="bg-white rounded-3xl p-5 md:p-8 shadow-xl border border-black/5">
        <h3 class="text-xs font-black uppercase tracking-widest opacity-40 mb-5 italic">Itens Selecionados</h3>
        
        <transition-group name="list" tag="div" class="space-y-5">
          <div v-for="(item, index) in itensNoCarrinho" :key="item.id" class="flex flex-col sm:flex-row items-center gap-4 pb-5 border-b border-gray-100 last:border-0 last:pb-0 group">
            
            <div class="w-16 h-16 bg-white rounded-xl flex items-center justify-center p-2 border border-black/5 shadow-sm">
              <img :src="item.foto" class="max-h-full max-w-full object-contain" />
            </div>
            
            <div class="flex-1 text-center sm:text-left">
              <p class="text-[9px] font-black text-[#2D4483] uppercase tracking-wider mb-0.5">{{ item.marca }}</p>
              <p class="font-bold text-xs uppercase leading-tight">{{ item.nome }}</p>
              <p class="text-[#1A1A1A] font-black mt-1.5 text-base">R$ {{ (item.preco * item.qtd).toFixed(2) }}</p>
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto justify-between sm:justify-end">
              <div class="flex items-center gap-2 bg-gray-50 p-1.5 rounded-xl border border-black/5">
                <button @click="item.qtd > 1 ? item.qtd-- : removerDoCarrinho(index)" class="w-9 h-9 flex items-center justify-center font-black bg-white rounded-lg shadow-sm hover:bg-gray-100">-</button>
                <span class="font-black text-base w-5 text-center">{{ item.qtd }}</span>
                <button @click="item.qtd++" class="w-9 h-9 flex items-center justify-center font-black bg-[#1A1A1A] text-white rounded-lg shadow-sm hover:bg-black">+</button>
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

      <section v-if="itensNoCarrinho.length > 0" class="bg-white rounded-3xl p-5 md:p-8 shadow-xl border border-black/5 space-y-4">
        <div class="flex justify-between items-center text-xs font-black uppercase tracking-widest opacity-40 italic">
          <h3>Local de Entrega</h3>
          <button @click="abrirModal('enderecos')" class="text-[#2D4483] hover:underline font-bold">Trocar Local</button>
        </div>
        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-2xl border border-black/5">
          <span class="text-2xl">📍</span>
          <div>
            <p class="font-black text-xs uppercase tracking-tight">{{ enderecoEntrega.titulo }}</p>
            <p class="text-[10px] text-gray-500 font-medium">{{ enderecoEntrega.rua }}, {{ enderecoEntrega.numero }} - {{ enderecoEntrega.cidade }}</p>
          </div>
        </div>
      </section>

      <section v-if="itensNoCarrinho.length > 0" class="bg-white rounded-3xl p-5 md:p-8 shadow-xl border border-black/5 space-y-5">
        <h3 class="text-xs font-black uppercase tracking-widest opacity-40 italic">Forma de Pagamento</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
          <button 
            v-for="metodo in metodos" :key="metodo.id" @click="metodoPago = metodo.id"
            :class="metodoPago === metodo.id ? 'bg-[#1A1A1A] text-white shadow-lg' : 'bg-gray-50 text-[#1A1A1A] opacity-60'"
            class="p-5 rounded-2xl border border-black/5 flex flex-col items-center gap-2 transition-all duration-200"
          >
            <img v-if="metodo.id === 'pix'" src="../assets/pix.png" class="w-8 h-8 object-contain" :class="metodoPago === 'pix' ? 'invert' : 'grayscale opacity-50'" />
            <span v-else class="text-2xl">{{ metodo.icon }}</span>
            <span class="font-black text-[9px] uppercase tracking-widest">{{ metodo.nome }}</span>
          </button>
        </div>
      </section>

      <section v-if="itensNoCarrinho.length > 0" class="bg-[#1A1A1A] text-white rounded-[2rem] p-8 shadow-2xl space-y-5 relative overflow-hidden">
        <div class="absolute -top-10 -right-10 w-32 h-32 bg-[#C2F2D9] rounded-full opacity-5"></div>

        <div class="border-b border-white/10 pb-5 space-y-3 relative z-10">
          <div class="flex justify-between items-center text-[10px] font-bold uppercase text-white/40 tracking-wider">
            <span>Subtotal</span>
            <span>R$ {{ subtotal }}</span>
          </div>
          <div class="flex justify-between items-center text-[10px] font-bold uppercase text-red-500 tracking-wider">
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
            <p class="text-5xl font-black italic tracking-tighter text-[#C2F2D9]">R$ {{ totalFinal }}</p>
          </div>
          <button @click="finalizarCompra" class="w-full sm:w-auto px-10 py-5 bg-[#C2F2D9] text-[#1A1A1A] font-black text-base rounded-2xl hover:scale-105 active:scale-95 transition-all shadow-xl uppercase tracking-widest">
            Finalizar Compra
          </button>
        </div>
      </section>

    </main>

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
            :class="enderecoEntrega.titulo === end.titulo ? 'border-[#1A1A1A] bg-gray-50' : 'border-transparent bg-white shadow-sm hover:border-black/10 hover:shadow-md'"
          >
            <div class="text-2xl">📍</div>
            <div class="flex-1">
              <p class="font-black text-xs uppercase">{{ end.titulo }}</p>
              <p class="text-[11px] text-gray-500 mt-1">{{ end.rua }}, {{ end.numero }}</p>
              <p class="text-[11px] text-gray-500">{{ end.bairro }} - {{ end.cidade }}</p>
            </div>
            <div v-if="enderecoEntrega.titulo === end.titulo" class="w-6 h-6 bg-[#1A1A1A] text-[#C2F2D9] rounded-full flex items-center justify-center text-xs font-black">
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
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

import arrozBranco from '../assets/arrozBranco.jpg'
import feijaoCarioca from '../assets/feijaoCarioca.jpg'

const router = useRouter()

// Controle do Modal
const modalAtivo = ref(null)
const mostrandoForm = ref(false)

// Dados de Pagamento e Resumo
const metodoPago = ref('pix')
const frete = ref(7.00)
const desconto = ref(3.50)

// Array de Endereços Simulado (O mesmo do perfil)
const enderecosSalvos = ref([
  { titulo: 'Casa', rua: 'Rua das Flores', numero: '123', bairro: 'Centro', cidade: 'Trindade - PE' },
  { titulo: 'Trabalho', rua: 'Av. Principal', numero: '450', bairro: 'Empresarial', cidade: 'Araripina - PE' }
])

const novoEndereco = ref({ titulo: '', cep: '', numero: '', rua: '' })

// O endereço de entrega começa sendo o primeiro da lista
const enderecoEntrega = ref(enderecosSalvos.value[0])

const itensNoCarrinho = ref([
  { id: 1, nome: 'Arroz Branco 1kg', marca: 'Camil', preco: 5.89, qtd: 2, foto: arrozBranco },
  { id: 2, nome: 'Feijão Carioca 1kg', marca: 'Kicaldo', preco: 8.90, qtd: 1, foto: feijaoCarioca }
])

const metodos = [
  { id: 'pix', nome: 'Pix', icon: '' },
  { id: 'cartao', nome: 'Cartão', icon: '💳' },
  { id: 'dinheiro', nome: 'Dinheiro', icon: '💵' }
]

const subtotal = computed(() => {
  return itensNoCarrinho.value.reduce((acc, item) => acc + (item.preco * item.qtd), 0).toFixed(2)
})

const totalFinal = computed(() => {
  const v = (parseFloat(subtotal.value) + frete.value - desconto.value)
  return v.toFixed(2)
})

const removerDoCarrinho = (index) => {
  if (confirm("Remover este produto?")) {
    itensNoCarrinho.value.splice(index, 1)
  }
}

// Funções do Modal de Endereço
const abrirModal = (tipo) => {
  modalAtivo.value = tipo
  mostrandoForm.value = false
}

const fecharModal = () => {
  modalAtivo.value = null
}

const selecionarEndereco = (end) => {
  enderecoEntrega.value = end
  fecharModal()
}

const salvarEndereco = () => {
  const novoEnd = {
    titulo: novoEndereco.value.titulo,
    rua: novoEndereco.value.rua,
    numero: novoEndereco.value.numero,
    bairro: 'Bairro Padrão',
    cidade: 'Cidade Nova - UF'
  }
  enderecosSalvos.value.push(novoEnd)
  
  // Opcional: Já seleciona o endereço novo automaticamente
  enderecoEntrega.value = novoEnd
  
  mostrandoForm.value = false
  fecharModal()
  novoEndereco.value = { titulo: '', cep: '', numero: '', rua: '' }
}

const finalizarCompra = () => {
  alert("Pedido enviado com sucesso para: " + enderecoEntrega.value.rua)
  router.push('/meus-pedidos')
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