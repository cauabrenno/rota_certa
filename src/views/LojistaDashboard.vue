<template>
  <div class="min-h-screen bg-[#F8FAFC] p-6">
    <div class="max-w-7xl mx-auto">
      
      <header class="flex justify-between items-center mb-10 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
          <h1 class="text-2xl font-black text-[#1A1A1A] uppercase tracking-tight italic">Painel Administrativo</h1>
          <p class="text-gray-400 font-bold text-xs uppercase tracking-widest">Gestão de Pedidos em Tempo Real</p>
        </div>
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-[#C2F2D9] rounded-2xl flex items-center justify-center font-black text-[#1A1A1A] shadow-inner">
            RC
          </div>
        </div>
      </header>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] border-b-8 border-purple-600 shadow-xl">
          <p class="text-gray-400 font-black text-[10px] uppercase mb-1">Pendentes</p>
          <p class="text-4xl font-black text-[#1A1A1A]">{{ contagem.pendentes }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border-b-8 border-blue-600 shadow-xl">
          <p class="text-gray-400 font-black text-[10px] uppercase mb-1">Em Preparo</p>
          <p class="text-4xl font-black text-[#1A1A1A]">{{ contagem.preparo }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border-b-8 border-orange-500 shadow-xl">
          <p class="text-gray-400 font-black text-[10px] uppercase mb-1">Em Rota</p>
          <p class="text-4xl font-black text-[#1A1A1A]">{{ contagem.rota }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border-b-8 border-green-600 shadow-xl">
          <p class="text-gray-400 font-black text-[10px] uppercase mb-1">Concluídos</p>
          <p class="text-4xl font-black text-[#1A1A1A]">{{ contagem.concluidos }}</p>
        </div>
      </div>

      <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl border border-gray-100">
        <h3 class="text-xl font-black mb-8 text-[#1A1A1A] uppercase italic">Monitoramento de Entregas</h3>
        
        <div class="space-y-4">
          <div v-for="pedido in pedidos" :key="pedido.id" class="flex flex-col md:flex-row items-center justify-between p-6 bg-gray-50 rounded-[2rem] hover:bg-gray-100 transition-all gap-4 border border-black/5">
            
            <div class="flex items-center gap-5 w-full md:w-auto">
              <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-md font-black text-[#1A1A1A] border border-black/5">
                #{{ pedido.id }}
              </div>
              <div>
                <p class="font-black text-[#1A1A1A] text-lg">{{ pedido.cliente }}</p>
                <p class="text-xs text-gray-400 font-medium uppercase">{{ pedido.endereco }}</p>
              </div>
            </div>
            
            <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end">
              <span :class="pedido.corStatus" class="px-5 py-2 rounded-full text-[10px] font-black uppercase text-white shadow-lg tracking-widest">
                {{ pedido.status }}
              </span>
              
              <button 
                @click="avancarStatus(pedido)"
                class="bg-[#1A1A1A] text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase hover:bg-black hover:scale-105 active:scale-95 transition-all shadow-xl tracking-widest"
              >
                Avançar ➔
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

const contagem = reactive({
  pendentes: 12,
  preparo: 5,
  rota: 8,
  concluidos: 45
})

const pedidos = ref([
  { id: 1021, cliente: 'Cauã Brenno', endereco: 'Rua Principal, 123', status: 'Pendente', corStatus: 'bg-purple-600' },
  { id: 1022, cliente: 'Nadson Silva', endereco: 'Av. das Flores, 45', status: 'Em Preparo', corStatus: 'bg-blue-600' },
  { id: 1023, cliente: 'Gemerson Lima', endereco: 'Rua do Comércio, 88', status: 'Em Rota', corStatus: 'bg-orange-500' },
])

const avancarStatus = (pedido) => {
  if (pedido.status === 'Pendente') {
    pedido.status = 'Em Preparo'
    pedido.corStatus = 'bg-blue-600'
    contagem.pendentes--
    contagem.preparo++
  } else if (pedido.status === 'Em Preparo') {
    pedido.status = 'Em Rota'
    pedido.corStatus = 'bg-orange-500'
    contagem.preparo--
    contagem.rota++
  } else if (pedido.status === 'Em Rota') {
    pedido.status = 'Concluído'
    pedido.corStatus = 'bg-green-600'
    contagem.rota--
    contagem.concluidos++
  }
}
</script>