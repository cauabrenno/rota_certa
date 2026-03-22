<template>
  <div class="min-h-screen bg-gradient-to-br from-[#F8FAFC] to-[#E2E8F0] p-6">
    <div class="max-w-4xl mx-auto">
      
      <header class="flex justify-between items-center mb-10">
        <div>
          <h1 class="text-3xl font-black text-rc-dark tracking-tighter">Meus Pedidos</h1>
          <p class="text-gray-500 font-medium">Acompanhe suas entregas em tempo real</p>
        </div>
        <router-link to="/login" class="bg-white p-3 rounded-2xl shadow-sm hover:scale-110 transition-transform">
          🚪
        </router-link>
      </header>

      <div class="space-y-6">
        <div v-for="pedido in historico" :key="pedido.id" 
             class="bg-white/80 backdrop-blur-md border border-white p-6 rounded-[2.5rem] shadow-xl shadow-gray-200/50 flex flex-col md:flex-row justify-between items-center gap-6">
          
          <div class="flex items-center gap-5 w-full">
            <div class="w-16 h-16 bg-rc-green/20 rounded-3xl flex items-center justify-center text-3xl shadow-inner">
              {{ pedido.icon }}
            </div>
            <div>
              <p class="text-[10px] font-black text-rc-blue uppercase tracking-widest">{{ pedido.data }}</p>
              <h3 class="text-xl font-bold text-rc-dark">{{ pedido.loja }}</h3>
              <p class="text-sm text-gray-400 font-medium italic">#{{ pedido.id }} • {{ pedido.itens }}</p>
            </div>
          </div>

          <div class="flex flex-col items-center md:items-end w-full md:w-auto gap-3">
            <span :class="pedido.statusCor" class="px-5 py-1.5 rounded-full text-[10px] font-black uppercase text-white shadow-lg">
              {{ pedido.status }}
            </span>
            
            <div v-if="pedido.status === 'Entregue'" class="flex gap-1 text-xl">
              <button v-for="i in 5" :key="i" @click="pedido.nota = i" 
                      :class="i <= pedido.nota ? 'text-yellow-400' : 'text-gray-200'" 
                      class="hover:scale-125 transition-transform">
                ★
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const historico = ref([
  { id: 985, loja: 'Mix Mateus', data: 'Hoje, 21 Mar', itens: '3 itens • R$ 89,90', status: 'Entregue', statusCor: 'bg-green-500', icon: '🛒', nota: 5 },
  { id: 942, loja: 'Farmácia Pague Menos', data: 'Ontem', itens: '1 item • R$ 25,00', status: 'Entregue', statusCor: 'bg-green-500', icon: '💊', nota: 4 },
  { id: 1023, loja: 'Mix Mateus', data: 'Em andamento', itens: '5 itens • R$ 120,50', status: 'Em Rota', statusCor: 'bg-orange-500', icon: '🛒', nota: 0 },
])
</script>