<template>
  <div class="relative h-screen w-full bg-gray-100 overflow-hidden font-sans">
    
    <div id="map" class="absolute inset-0 z-0"></div>

    <div 
      class="absolute bottom-0 w-full bg-white rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.08)] p-6 pb-8 transition-all duration-500 z-10"
      :class="(statusPedido !== null) ? 'translate-y-full' : 'translate-y-0'"
    >
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-xl font-black text-[#1A1A1A] italic tracking-tighter">Olá, Cauã</h2>
          <p class="text-sm text-gray-500 font-medium">Ganhos de hoje: <span class="text-green-600 font-bold">R$ 0,00</span></p>
        </div>
        <button @click="isProfileOpen = true" class="w-12 h-12 bg-gray-200 rounded-full border-2 border-[#1A1A1A] flex items-center justify-center overflow-hidden hover:scale-105 active:scale-95 transition-transform">
           <svg class="w-6 h-6 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
        </button>
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

    <transition enter-active-class="transition-all ease-out duration-500" enter-from-class="transform translate-y-full opacity-0" enter-to-class="transform translate-y-0 opacity-100" leave-active-class="transition-all ease-in duration-300" leave-from-class="transform translate-y-0 opacity-100" leave-to-class="transform translate-y-full opacity-0">
      <div v-if="statusPedido === 'pendente'" class="absolute bottom-4 left-4 right-4 bg-white rounded-3xl shadow-2xl p-6 z-20 border border-gray-100">
        <div class="flex justify-between items-start mb-6">
          <div class="bg-yellow-100 text-yellow-800 text-xs font-black uppercase px-3 py-1.5 rounded-lg animate-pulse border border-yellow-200">Nova Chamada</div>
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
            </div>
          </div>
          <div class="flex gap-4 items-start relative z-10">
            <div class="w-6 h-6 rounded-full bg-green-500 border-[3px] border-white shadow flex-shrink-0 mt-1"></div>
            <div>
              <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Entrega</p>
              <p class="font-bold text-[#1A1A1A] leading-tight text-lg">Rua São Pedro, Centro</p>
            </div>
          </div>
        </div>
        <div class="relative w-full h-[4.5rem] bg-gray-100 rounded-2xl overflow-hidden shadow-inner border border-black/5">
          <div class="absolute left-0 top-0 h-full bg-green-500 transition-none" :style="{ width: swipeProgress + '%' }"></div>
          <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
            <span class="font-black uppercase tracking-widest text-sm transition-colors duration-300" :class="swipeProgress > 50 ? 'text-white' : 'text-[#1A1A1A]/40'">
              Deslize para aceitar
            </span>
          </div>
          <div class="absolute top-1.5 bottom-1.5 bg-white rounded-xl shadow-md flex items-center justify-center z-20" :style="{ left: `${swipeProgress}%`, transform: `translateX(-${swipeProgress}%)`, width: '4rem' }">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path></svg>
          </div>
          <input type="range" min="0" max="100" v-model="swipeProgress" @touchend="checkSwipeAccept" @mouseup="checkSwipeAccept" class="absolute inset-0 w-full h-full opacity-0 z-30 cursor-pointer">
        </div>
        <div class="mt-4 text-center">
          <button @click="recusarPedido" class="text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-widest transition-colors">Recusar Chamada</button>
        </div>
      </div>
    </transition>

    <transition enter-active-class="transition-all ease-out duration-500" enter-from-class="transform translate-y-full opacity-0" enter-to-class="transform translate-y-0 opacity-100" leave-active-class="transition-all ease-in duration-300" leave-from-class="transform translate-y-0 opacity-100" leave-to-class="transform translate-y-full opacity-0">
      <div v-if="['aceito', 'coletado', 'em_rota'].includes(statusPedido)" class="absolute bottom-4 left-4 right-4 bg-[#1A1A1A] text-white rounded-3xl shadow-2xl p-6 z-20">
        <div class="flex items-center gap-3 mb-6">
          <span class="relative flex h-4 w-4">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" :class="dadosDaFaseAtual.pingColor"></span>
            <span class="relative inline-flex rounded-full h-4 w-4 border-2 border-[#1A1A1A]" :class="dadosDaFaseAtual.pingColor"></span>
          </span>
          <h3 class="text-lg font-black uppercase tracking-widest">{{ dadosDaFaseAtual.titulo }}</h3>
        </div>
        <div class="mb-8">
          <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">{{ dadosDaFaseAtual.labelInfo }}</p>
          <p class="font-bold text-2xl leading-tight">{{ dadosDaFaseAtual.infoPrincipal }}</p>
          <p class="text-sm text-gray-400 mt-1">{{ dadosDaFaseAtual.infoSecundaria }}</p>
        </div>
        <div class="relative w-full h-[4.5rem] bg-white/10 rounded-2xl overflow-hidden shadow-inner">
          <div class="absolute left-0 top-0 h-full transition-none" :class="dadosDaFaseAtual.barColor" :style="{ width: swipeFinishProgress + '%' }"></div>
          <div class="absolute inset-0 flex items-center justify-center pointer-events-none z-10">
            <span class="font-black uppercase tracking-widest text-sm transition-colors duration-300" :class="swipeFinishProgress > 50 ? 'text-white' : 'text-white/50'">
              {{ dadosDaFaseAtual.textoBotao }}
            </span>
          </div>
          <div class="absolute top-1.5 bottom-1.5 bg-white rounded-xl shadow-md flex items-center justify-center z-20" :style="{ left: `${swipeFinishProgress}%`, transform: `translateX(-${swipeFinishProgress}%)`, width: '4rem' }">
            <svg class="w-6 h-6" :class="dadosDaFaseAtual.iconColor" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
          </div>
          <input type="range" min="0" max="100" v-model="swipeFinishProgress" @touchend="checkSwipeFases" @mouseup="checkSwipeFases" class="absolute inset-0 w-full h-full opacity-0 z-30 cursor-pointer">
        </div>
      </div>
    </transition>

    <transition enter-active-class="transition-all ease-out duration-300" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition-all ease-in duration-200" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
      <div v-if="showNavModal" class="absolute inset-0 z-[80] flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showNavModal = false"></div>
        <div class="relative w-full max-w-sm bg-white rounded-[2.5rem] p-8 shadow-2xl text-center">
          <h3 class="text-xl font-black text-[#1A1A1A] italic tracking-tighter mb-6">Iniciar Rota com:</h3>
          
          <div class="space-y-4">
            <button @click="abrirGoogleMaps" class="w-full py-3 bg-gray-50 text-gray-800 font-black rounded-2xl hover:bg-gray-100 transition-all uppercase tracking-widest border border-gray-200 flex items-center justify-center gap-3 shadow-sm">
              <img :src="mapsLogo" alt="Google Maps" class="w-8 h-8 rounded-lg object-cover">
              Google Maps
            </button>
            <button @click="abrirWaze" class="w-full py-3 bg-[#e8f4f8] text-[#05c8f7] font-black rounded-2xl hover:bg-cyan-100 transition-all uppercase tracking-widest border border-cyan-100 flex items-center justify-center gap-3 shadow-sm">
              <img :src="wazeLogo" alt="Waze" class="w-8 h-8 object-contain">
              Waze
            </button>
          </div>

          <button @click="showNavModal = false" class="mt-6 text-xs font-bold text-gray-400 hover:text-red-500 uppercase tracking-widest">Fechar</button>
        </div>
      </div>
    </transition>

    <transition enter-active-class="transition-all ease-out duration-300" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition-all ease-in duration-200" leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
      <div v-if="showPinModal" class="absolute inset-0 z-[70] flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="fecharModalPin"></div>
        <div class="relative w-full max-w-sm bg-white rounded-[2.5rem] p-8 shadow-2xl text-center">
          <div class="w-16 h-16 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
          </div>
          <h3 class="text-2xl font-black text-[#1A1A1A] italic tracking-tighter mb-1">Código de Entrega</h3>
          <p class="text-sm text-gray-500 font-medium mb-8">Peça o código de 4 dígitos ao cliente.</p>
          
          <input type="text" maxlength="4" v-model="codigoCliente" placeholder="0000" class="w-full text-center text-5xl font-black tracking-[0.5em] text-[#1A1A1A] bg-gray-50 border-2 border-gray-200 rounded-2xl py-4 focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/20 transition-all mb-4 placeholder-gray-300">
          
          <p class="text-xs text-red-500 font-bold mb-4">🤫 Dica de dev: O PIN gerado é {{ pinCorretoGerado }}</p>

          <button @click="confirmarEntrega" class="w-full py-4 bg-[#1A1A1A] text-white font-black rounded-2xl hover:bg-black transition-all uppercase tracking-widest shadow-xl">Confirmar e Finalizar</button>
          <button @click="fecharModalPin" class="mt-4 text-xs font-bold text-gray-400 hover:text-[#1A1A1A] uppercase tracking-widest">Cancelar</button>
        </div>
      </div>
    </transition>

    <transition enter-active-class="transition-all ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition-all ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
      <div v-if="isProfileOpen" class="absolute inset-0 z-[60] flex flex-col justify-end">
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="isProfileOpen = false"></div>
        <div class="relative w-full bg-white rounded-t-[3rem] p-8 pb-10 shadow-2xl flex flex-col gap-6" @click.stop>
          <div class="flex justify-between items-center border-b border-black/5 pb-4">
            <h2 class="text-2xl font-black text-[#1A1A1A] italic tracking-tighter">Meu Perfil</h2>
            <button @click="isProfileOpen = false" class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 hover:bg-gray-200">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 bg-[#1A1A1A] rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">C</div>
            <div>
              <p class="font-bold text-lg text-[#1A1A1A] leading-tight">Cauã</p>
              <div class="flex items-center gap-1 mt-1 text-sm font-bold text-yellow-500">
                ⭐ 4.9 <span class="text-gray-400 font-medium text-xs ml-1">(124 entregas)</span>
              </div>
            </div>
          </div>
          <div class="bg-[#1A1A1A] rounded-2xl p-5 shadow-lg text-white space-y-4">
            <div class="flex justify-between items-center border-b border-white/10 pb-3">
              <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-xs font-bold uppercase tracking-widest text-gray-300">Saldo da Semana</span>
              </div>
              <button class="text-xs font-bold text-green-400 hover:text-green-300 uppercase tracking-wider transition-colors">Ver Histórico</button>
            </div>
            <div>
              <p class="text-3xl font-black tracking-tighter">R$ 342,50</p>
              <p class="text-xs font-medium text-gray-400 mt-1">Último repasse: Há 2 dias</p>
            </div>
          </div>
          <div class="bg-gray-50 rounded-2xl p-4 border border-black/5 space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Veículo</span>
              <span class="font-bold text-[#1A1A1A]">Honda CG 160 • ABC-1234</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">CNH</span>
              <span class="text-xs font-bold text-green-600 bg-green-100 px-2 py-1 rounded-md uppercase">Validada</span>
            </div>
          </div>
          <button @click="realizarLogout" class="w-full py-4 bg-red-50 text-red-500 font-black rounded-2xl hover:bg-red-100 transition-all uppercase tracking-widest shadow-sm mt-2">
            Sair do Sistema
          </button>
        </div>
      </div>
    </transition>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'
import mapsLogo from '../assets/mapsLogo.png'
import wazeLogo from '../assets/wazeLogo.png'

const router = useRouter()

// 💾 FUNÇÕES DE SALVAMENTO BLINDADO (SÍNCRONO)
const atualizarStatus = (novoStatus) => {
  statusPedido.value = novoStatus;
  if (novoStatus) localStorage.setItem('statusPedido', novoStatus);
  else localStorage.removeItem('statusPedido');
}

const atualizarOnline = (statusOnline) => {
  isOnline.value = statusOnline;
  localStorage.setItem('isOnline', statusOnline);
}

const atualizarPin = (novoPin) => {
  pinCorretoGerado.value = novoPin;
  if (novoPin) localStorage.setItem('pinCorretoGerado', novoPin);
  else localStorage.removeItem('pinCorretoGerado');
}

// 💾 LEITURA SEGURA DO COFRE INICIAL
const carregarStatusSeguro = () => {
  const salvo = localStorage.getItem('statusPedido');
  return (salvo && salvo !== 'null' && salvo !== 'undefined') ? salvo : null;
}

const isOnline = ref(localStorage.getItem('isOnline') === 'true')
const statusPedido = ref(carregarStatusSeguro())
const pinCorretoGerado = ref(localStorage.getItem('pinCorretoGerado') || '')

const showPinModal = ref(false)
const isProfileOpen = ref(false) 
const swipeProgress = ref(0) 
const swipeFinishProgress = ref(0) 
const codigoCliente = ref('') 

const showNavModal = ref(false)
const navDestinoLat = ref(0)
const navDestinoLng = ref(0)

let map = null 
let marker = null 

const dadosDaFaseAtual = computed(() => {
  switch(statusPedido.value) {
    case 'aceito': return {
      titulo: 'Indo Coletar', pingColor: 'bg-orange-500', barColor: 'bg-orange-500', iconColor: 'text-orange-500',
      labelInfo: 'Coletar em', infoPrincipal: 'Laticínios Deleite', infoSecundaria: 'Falar com: Atendimento',
      textoBotao: 'Deslize se Coletou'
    }
    case 'coletado': return {
      titulo: 'Pedido Coletado', pingColor: 'bg-indigo-500', barColor: 'bg-indigo-500', iconColor: 'text-indigo-500',
      labelInfo: 'Próximo passo', infoPrincipal: 'Iniciar Viagem', infoSecundaria: 'Tudo pronto na bag?',
      textoBotao: 'Deslize para Iniciar Rota'
    }
    case 'em_rota': return {
      titulo: 'Em Rota de Entrega', pingColor: 'bg-green-500', barColor: 'bg-green-500', iconColor: 'text-green-500',
      labelInfo: 'Entregar em', infoPrincipal: 'Rua São Pedro, Centro', infoSecundaria: 'Cliente: João Silva',
      textoBotao: 'Deslize se Chegou' 
    }
    default: return {}
  }
})

const iniciarSimuladorSeLivre = () => {
  if (isOnline.value && statusPedido.value === null) {
    setTimeout(() => { 
      if (isOnline.value && statusPedido.value === null) {
        atualizarStatus('pendente');
        atualizarPin(Math.floor(1000 + Math.random() * 9000).toString());
      }
    }, 3000) 
  }
}

const toggleStatus = () => { 
  atualizarOnline(!isOnline.value);
  if (isOnline.value) {
    iniciarSimuladorSeLivre()
  } else {
    atualizarStatus(null);
    swipeProgress.value = 0
  }
}

const prepararNavegacao = (lat, lng) => {
  navDestinoLat.value = lat;
  navDestinoLng.value = lng;
  showNavModal.value = true;
}

const abrirGoogleMaps = () => {
  window.open(`https://www.google.com/maps/dir/?api=1&destination=${navDestinoLat.value},${navDestinoLng.value}&travelmode=driving`, '_blank');
  showNavModal.value = false;
}

const abrirWaze = () => {
  window.open(`https://waze.com/ul?ll=${navDestinoLat.value},${navDestinoLng.value}&navigate=yes`, '_blank');
  showNavModal.value = false;
}

const checkSwipeAccept = () => {
  if (swipeProgress.value > 90) { 
    swipeProgress.value = 100
    
    // 💾 Salva OBRIGATORIAMENTE ANTES de abrir qualquer outra coisa
    atualizarStatus('aceito'); 
    swipeProgress.value = 0;
    
    setTimeout(() => {
      prepararNavegacao(-7.2050, -39.3100);
    }, 300);
    
  } else {
    swipeProgress.value = 0
  }
}

const checkSwipeFases = () => {
  if (swipeFinishProgress.value > 90) {
    swipeFinishProgress.value = 100
    
    setTimeout(() => {
      if (statusPedido.value === 'aceito') {
        atualizarStatus('coletado');
      } 
      else if (statusPedido.value === 'coletado') {
        atualizarStatus('em_rota');
        prepararNavegacao(-7.2100, -39.3150);
      } 
      else if (statusPedido.value === 'em_rota') {
        showPinModal.value = true;
      }
      
      swipeFinishProgress.value = 0;
    }, 300)
  } else {
    swipeFinishProgress.value = 0
  }
}

const confirmarEntrega = () => {
  if (codigoCliente.value === pinCorretoGerado.value) {
    alert("✅ Entrega finalizada com sucesso! O dinheiro já está na sua carteira.")
    showPinModal.value = false
    atualizarStatus(null);
    atualizarPin('');
    codigoCliente.value = ''
    iniciarSimuladorSeLivre() 
  } else {
    alert("⚠️ Código incorreto! O código desta entrega é: " + pinCorretoGerado.value)
  }
}

const fecharModalPin = () => {
  showPinModal.value = false
  codigoCliente.value = ''
}

const recusarPedido = () => {
  atualizarStatus(null);
  swipeProgress.value = 0
  iniciarSimuladorSeLivre()
}

const realizarLogout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('isOnline');
  localStorage.removeItem('statusPedido');
  localStorage.removeItem('pinCorretoGerado');
  router.push('/');
}

onMounted(() => {
  iniciarSimuladorSeLivre();

  map = L.map('map', { zoomControl: false }).setView([-7.2016, -39.3182], 15)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map)
  
  const motoIcon = L.divIcon({
    className: 'bg-transparent',
    html: `<div class="bg-[#1A1A1A] w-12 h-12 rounded-full border-[3px] border-white shadow-xl flex items-center justify-center text-2xl animate-bounce">🏍️</div>`,
    iconSize: [48, 48], iconAnchor: [24, 48]
  })
  
  marker = L.marker([-7.2016, -39.3182], { icon: motoIcon }).addTo(map)

  if ("geolocation" in navigator) {
    navigator.geolocation.watchPosition(
      (position) => {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;
        marker.setLatLng([lat, lng]);
        map.setView([lat, lng]); 
      },
      (error) => {
        console.error("Erro ao capturar GPS:", error.message);
      },
      { 
        enableHighAccuracy: true,
        maximumAge: 0, 
        timeout: 5000 
      }
    );
  } else {
    console.log("Seu navegador não suporta GPS.");
  }
})
</script>

<style>
.leaflet-control-attribution { display: none !important; }
input[type="range"] { -webkit-appearance: none; background: transparent; }
input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; height: 80px; width: 80px; background: transparent; cursor: pointer; }
input[type="range"]::-moz-range-thumb { height: 80px; width: 80px; background: transparent; cursor: pointer; border: none; }
</style>