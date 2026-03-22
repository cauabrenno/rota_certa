<template>
  <div class="min-h-screen bg-[#F8FAFC] p-6">
    <div class="max-w-7xl mx-auto">
      <header class="flex justify-between items-center mb-10 bg-white p-6 rounded-3xl shadow-sm border border-gray-100">
        <div>
          <h1 class="text-2xl font-black text-rc-dark uppercase tracking-tight">Painel Administrativo</h1>
          <p class="text-gray-400 font-medium text-sm">Bem-vindo, Mix Mateus Ouricuri</p>
        </div>
        <div class="flex items-center gap-4">
          <div class="w-12 h-12 bg-rc-green rounded-2xl flex items-center justify-center font-bold text-rc-dark shadow-inner">MM</div>
        </div>
      </header>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[2rem] border-b-8 border-purple-500 shadow-xl">
          <p class="text-gray-400 font-bold text-xs uppercase mb-1">Pendentes</p>
          <p class="text-4xl font-black text-rc-dark">{{ contagem.pendentes }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border-b-8 border-blue-500 shadow-xl">
          <p class="text-gray-400 font-bold text-xs uppercase mb-1">Em Preparo</p>
          <p class="text-4xl font-black text-rc-dark">{{ contagem.preparo }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border-b-8 border-orange-500 shadow-xl">
          <p class="text-gray-400 font-bold text-xs uppercase mb-1">Em Rota</p>
          <p class="text-4xl font-black text-rc-dark">{{ contagem.rota }}</p>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border-b-8 border-green-500 shadow-xl">
          <p class="text-gray-400 font-bold text-xs uppercase mb-1">Concluídos</p>
          <p class="text-4xl font-black text-rc-dark">{{ contagem.concluidos }}</p>
        </div>
      </div>

      <div class="bg-white rounded-[2.5rem] p-8 shadow-2xl border border-gray-50">
        <h3 class="text-xl font-black mb-6 text-rc-dark">Monitoramento em Tempo Real</h3>
        <div class="space-y-4">
          <div v-for="pedido in pedidos" :key="pedido.id" class="flex items-center justify-between p-5 bg-gray-50 rounded-2xl hover:bg-rc-green/10 transition-all">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm font-bold">#{{ pedido.id }}</div>
              <div>
                <p class="font-bold text-rc-dark">Cliente: {{ pedido.cliente }}</p>
                <p class="text-xs text-gray-400">{{ pedido.endereco }}</p>
              </div>
            </div>
            
            <div class="flex items-center gap-4">
              <span :class="pedido.corStatus" class="px-4 py-1.5 rounded-full text-xs font-black uppercase text-white shadow-md">
                {{ pedido.status }}
              </span>
              
              <button 
                @click="avancarStatus(pedido)"
                class="bg-rc-dark text-white px-5 py-2 rounded-xl text-xs font-black uppercase hover:scale-110 active:scale-95 transition-all shadow-lg"
              >
                Próxima Etapa ➔
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

// Contagem inicial (simulando os cards lá em cima)
const contagem = reactive({
  pendentes: 12,
  preparo: 5,
  rota: 8,
  concluidos: 45
})

// Lista de pedidos reativa
const pedidos = ref([
  { id: 1021, cliente: 'Cauã Brenno', endereco: 'Rua Cel. Anísio Lustosa', status: 'Pendente', corStatus: 'bg-purple-500' },
  { id: 1022, cliente: 'Nadson Silva', endereco: 'Av. Antonio Pedro da Silva', status: 'Em Preparo', corStatus: 'bg-blue-500' },
  { id: 1023, cliente: 'Gemerson Lima', endereco: 'Perto do Mix Mateus', status: 'Em Rota', corStatus: 'bg-orange-500' },
])

// Lógica para mudar o status ao clicar no botão
const avancarStatus = (pedido) => {
  if (pedido.status === 'Pendente') {
    pedido.status = 'Em Preparo'
    pedido.corStatus = 'bg-blue-500'
    contagem.pendentes--
    contagem.preparo++
  } else if (pedido.status === 'Em Preparo') {
    pedido.status = 'Em Rota'
    pedido.corStatus = 'bg-orange-500'
    contagem.preparo--
    contagem.rota++
  } else if (pedido.status === 'Em Rota') {
    pedido.status = 'Concluído'
    pedido.corStatus = 'bg-green-500'
    contagem.rota--
    contagem.concluidos++
  }
}
</script>