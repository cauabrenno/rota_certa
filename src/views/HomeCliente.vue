<template>
  <div class="min-h-screen bg-[#F8FAFC] pb-10">
    
    <nav class="bg-white p-6 sticky top-0 z-50 shadow-sm border-b border-black/5 flex justify-between items-center px-8">
      <div class="flex items-center gap-2">
        <span class="text-2xl">⚡</span>
        <h1 class="text-xl font-black text-[#1A1A1A] italic uppercase">RotaCerta</h1>
      </div>
      
      <div class="flex gap-6 items-center">
        <button class="text-2xl">👤</button>
        <button class="relative text-2xl">
          🛒
          <span class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold">0</span>
        </button>
      </div>
    </nav>

    <main class="p-6 max-w-7xl mx-auto">
      
      <div class="mb-10">
        <input 
          type="text" 
          placeholder="O que você procura?" 
          class="w-full p-5 rounded-[2rem] bg-white shadow-lg border-none outline-none focus:ring-2 focus:ring-[#C2F2D9] text-[#1A1A1A]"
        />
      </div>

      <h3 class="text-xl font-black text-[#1A1A1A] mb-6 uppercase">Destaques</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 mb-12">
        <div v-for="n in 5" :key="n" class="bg-white p-4 rounded-[2rem] shadow-md hover:scale-105 transition-transform border border-black/5">
          <div class="aspect-square bg-gray-100 rounded-2xl mb-3 overflow-hidden">
            <img src="https://img.freepik.com/fotos-gratis/hamburguer-de-carne-com-queijo-e-salada-fresca-em-fundo-isolado_185193-54150.jpg" class="w-full h-full object-cover" />
          </div>
          <p class="font-black text-[#1A1A1A] text-xs">Burger Prime #{{n}}</p>
          <p class="text-[#2D4483] font-bold text-sm mt-1">R$ 32,90</p>
        </div>
      </div>

      <h3 class="text-xl font-black text-[#1A1A1A] mb-6 uppercase">Lojas Parceiras</h3>
      <div class="flex gap-6 overflow-x-auto pb-4">
        <div v-for="n in 4" :key="n" class="min-w-[120px]">
          <div class="w-24 h-24 bg-white rounded-full shadow-lg flex items-center justify-center p-4 mx-auto border-2 border-transparent hover:border-[#C2F2D9] transition-all cursor-pointer">
            <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Logo_Mix_Mateus.png" class="w-full h-auto grayscale hover:grayscale-0 transition-all" />
          </div>
          <p class="text-center mt-3 font-bold text-[#1A1A1A] text-[10px] uppercase">Mix Mateus</p>
        </div>
      </div>

    </main>
  </div>
</template>

<script setup>
// Por enquanto não precisamos de lógica complexa, só o visual
</script>