<template>
  <div class="relative h-screen w-full bg-gray-100 overflow-hidden font-sans">
    
    <div id="map" class="absolute inset-0 z-0"></div>

    <div 
      class="absolute bottom-0 w-full bg-white rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.08)] p-6 pb-8 transition-all duration-500 z-10"
      :class="isOrderPending ? 'translate-y-full' : 'translate-y-0'"
    >
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-xl font-black text-[#1A1A1A] italic tracking-tighter">Olá, Entregador</h2>
          <p class="text-sm text-gray-500 font-medium">Ganhos de hoje: <span class="text-green-600 font-bold">R$ 0,00</span></p>
        </div>
        <div class="w-12 h-12 bg-gray-200 rounded-full border-2 border-[#1A1A1A] flex items-center justify-center overflow-hidden">
           <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
        </div>
      </div>

      <button 
        @click="toggleStatus"
        :class="isOnline ? 'bg-green-500 shadow-green-500/30' : 'bg-[#1A1A1A] shadow-black/30'"
        class="w-full py-4 rounded-2xl font-black text-white uppercase tracking-widest text-lg transition-all duration-300 shadow-xl flex items-center justify-center gap-3"
      >
        <span class="relative flex h-3 w-3">
          <span v-if="isOnline" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
          <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
        </span>
        {{ isOnline ? 'Você está Online' : 'Ficar Online' }}
      </button>
    </div>

    <transition
      enter-active-class="transition-all ease-out duration-500"
      enter-from-class="transform translate-y-full opacity-0"
      enter-to-class="transform translate-y-0 opacity-100"
      leave-active-class="transition-all ease-in duration-300"
      leave-from-class="transform translate-y-0 opacity-100"
      leave-to-class="transform translate-y-full opacity-0"
    >
      <div v-if="isOrderPending" class="absolute bottom-4 left-4 right-4 bg-white rounded-3xl shadow-2xl p-6 z-50 border border-gray-100">
        
        <div class="flex justify-between items-start mb-6">
          <div class="bg-yellow-100 text-yellow-800 text-xs font-black uppercase px-3 py-1.5 rounded-lg animate-pulse border border-yellow-200">
            Nova Chamada
          </div>
          <div class="text-right">
            <p class="text-3xl font-black text-green-500 tracking-tighter">R$ 14,50</p>
            <p class="text-xs font-bold text-gray-400 uppercase">Ganhos da entrega</p>
          </div>
        </div>

        <div class="space-y-5 mb-8 relative ml-2">
          <div class="absolute left-[11px] top-6 bottom-6 w-0.5 bg-gray-200"></div>
          
          <div class="flex gap-4 items-start relative z-10">
            <div class="w-6 h-6 rounded-full bg-[#1A1A1A] border-[3px] border-white shadow flex-shrink-0 mt-1"></div>
            <div>
              <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Coleta</p>
              <p class="font-bold text-[#1A1A1A] leading-tight text-lg">Laticínios Deleite do Araripe</p>
              <p class="text-sm text-gray-500 font-medium mt-0.5">A 1.2 km de você</p>
            </div>
          </div>

          <div class="flex gap-4 items-start relative z-10">
            <div class="w-6 h-6 rounded-full bg-green-500 border-[3px] border-white shadow flex-shrink-0 mt-1"></div>
            <div>
              <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Entrega</p>
              <p class="font-bold text-[#1A1A1A] leading-tight text-lg">Rua São Pedro, Centro</p>
              <p class="text-sm text-gray-500 font-medium mt-0.5">Distância total: 4.5 km</p>
            </div>
          </div>
        </div>

        <div class="relative w-full h-[4.5rem] bg-gray-100 rounded-2xl overflow-hidden shadow-inner border border-black/5">
          
          <div class="absolute left-0 top-0 h-full bg-green-500 transition-none" :style="{ width: swipeProgress + '%' }"></div>
          
          <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
            <span class="font-black uppercase tracking-widest text-sm transition-colors duration-300" :class="swipeProgress > 50 ? 'text-white' : 'text-[#1A1A1A]/40'">
              {{ swipeProgress > 85 ? 'Solte para Aceitar' : 'Deslize para aceitar' }}
            </span>
          </div>

          <div 
            class="absolute top-1.5 bottom-1.5 bg-white rounded-xl shadow-md flex items-center justify-center z-20" 
            :style="{ 
              left: `${swipeProgress}%`, 
              transform: `translateX(-${swipeProgress}%)`,
              width: '4rem' 
            }"
          >
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
          </div>

          <input 
            type="range" 
            min="0" 
            max="100" 
            v-model="swipeProgress" 
            @touchend="checkSwipeEnd"
            @mouseup="checkSwipeEnd"
            class="absolute inset-0 w-full h-full opacity-0 z-30 cursor-pointer"
          >
        </div>
        
        <div class="mt-4 text-center">
          <button @click="recusarPedido" class="text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors">
            Recusar Chamada
          </button>
        </div>

      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const isOnline = ref(false)
const isOrderPending = ref(false)
const swipeProgress = ref(0)
let map = null 
let marker = null 

// Função de ficar Online/Offline
const toggleStatus = () => {
  isOnline.value = !isOnline.value
}

// Simulador: Ouve a variável isOnline. Se ficar true, toca o pedido em 3s.
watch(isOnline, (online) => {
  if (online) {
    setTimeout(() => {
      isOrderPending.value = true // Mostra o card do pedido!
    }, 3000) 
  } else {
    isOrderPending.value = false
    swipeProgress.value = 0
  }
})

// O que acontece quando o cara solta o dedo no final da barra?
const checkSwipeEnd = () => {
  if (swipeProgress.value > 90) { // Se arrastou mais de 90%, aceita!
    swipeProgress.value = 100
    aceitarPedido()
  } else {
    // Se não arrastou até o final, o botão volta pro começo (efeito elástico)
    swipeProgress.value = 0
  }
}

const aceitarPedido = () => {
  setTimeout(() => {
    alert("🚀 Pedido Aceito! Na vida real, abriríamos o Google Maps aqui.")
    isOrderPending.value = false // Esconde o card
    swipeProgress.value = 0 // Reseta o botão
  }, 300)
}

const recusarPedido = () => {
  isOrderPending.value = false
  swipeProgress.value = 0
}

onMounted(() => {
  map = L.map('map', { zoomControl: false }).setView([-7.2016, -39.3182], 15)

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19, attribution: '© OpenStreetMap'
  }).addTo(map)

  const motoIcon = L.divIcon({
    className: 'bg-transparent',
    html: `
      <div class="bg-[#1A1A1A] w-12 h-12 rounded-full border-[3px] border-white shadow-xl flex items-center justify-center text-2xl animate-bounce">
        🏍️
      </div>
    `,
    iconSize: [48, 48], iconAnchor: [24, 48]
  })

  marker = L.marker([-7.2016, -39.3182], { icon: motoIcon }).addTo(map)
})
</script>

<style>
.leaflet-control-attribution { display: none !important; }
</style>