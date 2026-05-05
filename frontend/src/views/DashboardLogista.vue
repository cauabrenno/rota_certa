<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] p-8 space-y-8">

    <!-- HEADER -->
    <div>
      <h1 class="text-3xl font-black italic uppercase tracking-tighter">
        Dashboard
      </h1>
      <p class="text-sm text-gray-500">Visão geral da loja</p>
    </div>

    <!-- CARDS TOPO -->
    <div class="grid grid-cols-5 gap-4">

      <div class="card-top border-purple-500">
        <p>Pedidos pendentes</p>
        <span>12</span>
      </div>

      <div class="card-top border-blue-500">
        <p>Pedidos em preparo</p>
        <span>8</span>
      </div>

      <div class="card-top border-orange-500">
        <p>Pedidos em entrega</p>
        <span>5</span>
      </div>

      <div class="card-top border-yellow-500">
        <p>Pedidos finalizados</p>
        <span>20</span>
      </div>

      <div class="card-top border-green-500">
        <p>Tempo médio</p>
        <span>32 min</span>
      </div>

    </div>

    <!-- GRID PRINCIPAL -->
    <div class="grid grid-cols-3 gap-6">

      <!-- FILA DE PEDIDOS -->
      <div class="col-span-2 bg-white rounded-[2rem] p-6 shadow-xl border border-black/5">

        <div class="flex justify-between items-center mb-4">
          <h2 class="font-black uppercase text-sm">Fila de Pedidos</h2>
          <select class="text-xs bg-gray-100 px-3 py-1 rounded-lg">
            <option>Todos</option>
            <option>Pendentes</option>
          </select>
        </div>

        <input 
          type="text" 
          placeholder="Buscar pedidos..." 
          class="w-full mb-4 p-3 rounded-xl bg-gray-100 text-sm outline-none"
        />

        <div class="space-y-3">

          <div class="pedido-row">
            <span>#1001</span>
            <span>João Silva</span>
            <span>Rua das Flores</span>
            <span class="status bg-green-100 text-green-600">Pronto</span>
          </div>

          <div class="pedido-row">
            <span>#1002</span>
            <span>Maria</span>
            <span>Av. Central</span>
            <span class="status bg-yellow-100 text-yellow-600">Preparo</span>
          </div>

        </div>

      </div>

      <!-- ENTREGADORES -->
      <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-black/5 flex flex-col">

        <div class="flex justify-between items-center mb-4">
          <h2 class="font-black uppercase text-sm">Entregadores</h2>
          <span>•••</span>
        </div>

        <div class="flex-1 flex items-center justify-center bg-gray-100 rounded-2xl">
          <span class="text-5xl opacity-30">🗺️</span>
        </div>

      </div>

      <!-- NOTIFICAÇÕES -->
      <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-black/5">

        <h2 class="font-black uppercase text-sm mb-4">Notificações</h2>

        <div class="space-y-3 text-xs">

          <div class="notif bg-blue-100 text-blue-700">
            Novo pedido recebido!
          </div>

          <div class="notif bg-yellow-100 text-yellow-700">
            Pedido #1008 atrasado
          </div>

          <div class="notif bg-green-100 text-green-700">
            Entregador disponível
          </div>

        </div>

      </div>

      <!-- ESTATÍSTICAS -->
      <div class="col-span-2 bg-white rounded-[2rem] p-6 shadow-xl border border-black/5">

        <h2 class="font-black uppercase text-sm mb-4">Estatísticas diárias</h2>

        <div class="grid grid-cols-3 gap-4">

          <div class="stat-card bg-[#2D4483] text-white">
            <p>Pedidos do dia</p>
            <span>24</span>
          </div>

          <div class="stat-card bg-red-500 text-white">
            <p>Atrasados</p>
            <span>3</span>
          </div>

          <div class="stat-card bg-yellow-400 text-black">
            <p>Avaliação</p>
            <span>⭐⭐⭐⭐☆</span>
          </div>

        </div>

      </div>

    </div>

  </div>
</template>

<style scoped>
.card-top {
  @apply bg-white p-4 rounded-2xl shadow-md border-b-4 text-xs font-black uppercase flex flex-col gap-2;
}
.card-top span {
  @apply text-xl font-black;
}

.pedido-row {
  @apply grid grid-cols-4 bg-gray-50 p-3 rounded-xl text-xs items-center;
}

.status {
  @apply px-2 py-1 rounded-full text-[10px] font-black text-center;
}

.notif {
  @apply p-3 rounded-xl font-medium;
}

.stat-card {
  @apply p-4 rounded-2xl font-black text-sm flex flex-col;
}
.stat-card span {
  @apply text-xl mt-2;
}
</style>