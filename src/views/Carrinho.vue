<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F1F7] pb-20 text-[#1A1A1A]">
    
    <nav class="bg-white/90 backdrop-blur-sm p-4 sticky top-0 z-40 shadow-sm border-b border-black/5 flex items-center px-6 gap-3">
      <router-link to="/home" class="text-xl hover:scale-110 transition-all">⬅️</router-link>
      <h1 class="text-lg font-black italic uppercase tracking-tighter">Carrinho</h1>
    </nav>

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
          <button @click="mudarEndereco" class="text-[#2D4483] hover:underline">Mudar</button>
        </div>
        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-black/5">
          <span class="text-2xl">📍</span>
          <p class="font-bold text-xs uppercase tracking-tight">{{ enderecoEntrega }}</p>
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
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const enderecoEntrega = ref('Rua das Flores, 123 - Centro')
const metodoPago = ref('pix')
const frete = ref(7.00)
const desconto = ref(3.50) // Desconto fixo para teste

const itensNoCarrinho = ref([
  { id: 1, nome: 'Arroz Branco 1kg', marca: 'Camil', preco: 5.89, qtd: 2, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS-v1hS01fL-7kMv6pX0r8E9_9y1o-k-Y7-rA&s' },
  { id: 2, nome: 'Leite Integral 1L', marca: 'Itambé', preco: 4.50, qtd: 1, foto: 'https://itambe.com.br/portal/images/produtos/leite-uht-itambe-integral-1l-370.png' }
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

const mudarEndereco = () => {
  const novo = prompt("Novo endereço:")
  if (novo) enderecoEntrega.value = novo
}

const finalizarCompra = () => {
  alert("Pedido enviado com sucesso!")
  router.push('/meus-pedidos')
}
</script>