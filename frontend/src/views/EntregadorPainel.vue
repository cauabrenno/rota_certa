<template>
  <div class="relative h-screen w-full bg-gray-100 overflow-hidden font-sans">
    
    <div id="map" class="absolute inset-0 z-0"></div>

    <div 
      class="absolute bottom-0 w-full bg-white rounded-t-[2.5rem] shadow-[0_-10px_40px_rgba(0,0,0,0.08)] p-6 pb-8 transition-all duration-500 z-10"
      :class="(statusPedido !== null) ? 'translate-y-full' : 'translate-y-0'"
    >
      <div class="flex justify-between items-center mb-6">
        <div>
          <h2 class="text-xl font-black text-[#1A1A1A] italic tracking-tighter">Olá, {{ dadosPerfil?.nome ? dadosPerfil.nome.split(' ')[0] : 'Entregador' }}</h2>
          <p class="text-sm text-gray-500 font-medium">Ganhos de hoje: <span class="text-green-600 font-bold">R$ {{ Number(dadosPerfil?.saldo_semana || 0).toFixed(2) }}</span></p>
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
      <div v-if="statusPedido === 'pendente' && corridaAtual" class="absolute bottom-4 left-4 right-4 bg-white rounded-3xl shadow-2xl p-6 z-20 border border-gray-100">
        <div class="flex justify-between items-start mb-6">
          <div class="bg-yellow-100 text-yellow-800 text-xs font-black uppercase px-3 py-1.5 rounded-lg animate-pulse border border-yellow-200">Nova Chamada</div>
          <div class="text-right">
            <p class="text-3xl font-black text-green-500 tracking-tighter">R$ {{ Number(corridaAtual.taxa_entrega).toFixed(2) }}</p>
            <p class="text-xs font-bold text-gray-400 uppercase">Ganhos da entrega</p>
          </div>
        </div>
        <div class="space-y-5 mb-8 relative ml-2">
          <div class="absolute left-[11px] top-6 bottom-6 w-0.5 bg-gray-200"></div>
          <div class="flex gap-4 items-start relative z-10">
            <div class="w-6 h-6 rounded-full bg-[#1A1A1A] border-[3px] border-white shadow flex-shrink-0 mt-1"></div>
            <div class="ml-1"> <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Coleta</p>
              <p class="font-bold text-[#1A1A1A] leading-tight text-lg">{{ corridaAtual.loja || 'Loja Parceira' }}</p>
            </div>
          </div>
          <div class="flex gap-4 items-start relative z-10">
            <div class="w-6 h-6 rounded-full bg-green-500 border-[3px] border-white shadow flex-shrink-0 mt-1"></div>
            <div class="ml-1"> <p class="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Entrega</p>
              <p class="font-bold text-[#1A1A1A] leading-tight text-lg truncate w-48">{{ formatarEndereco(corridaAtual.endereco_entrega || corridaAtual.endereco) }}</p>
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
          <p class="font-bold text-2xl leading-tight truncate w-full">{{ dadosDaFaseAtual.infoPrincipal }}</p>
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

<transition 
      enter-active-class="transition-all ease-out duration-500" 
      enter-from-class="opacity-0 translate-y-full" 
      enter-to-class="opacity-100 translate-y-0" 
      leave-active-class="transition-all ease-in duration-400" 
      leave-from-class="opacity-100 translate-y-0" 
      leave-to-class="opacity-0 translate-y-full"
    >
      <div v-if="isProfileOpen" class="absolute inset-0 z-[100] flex flex-col justify-end bg-[#1A1A1A]/60 backdrop-blur-sm">
        
        <div class="absolute inset-0 z-0" @click="isProfileOpen = false"></div>

        <div class="relative w-full bg-white rounded-t-[3.5rem] shadow-[0_-20px_60px_rgba(0,0,0,0.2)] flex flex-col z-10 overflow-hidden">
          
          <div class="w-full flex justify-center py-4">
            <div class="w-12 h-1.5 bg-gray-200 rounded-full"></div>
          </div>

          <div class="px-8 pb-10 flex flex-col gap-6">
            
            <div class="flex justify-between items-center">
              <div>
                <h2 class="text-3xl font-black text-[#1A1A1A] italic tracking-tighter uppercase leading-none">Perfil</h2>
                <div class="flex items-center gap-2 mt-1">
                  <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse"></div>
                  <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Entregador Verificado</p>
                </div>
              </div>
              <button @click="isProfileOpen = false" class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center text-gray-400 hover:text-[#1A1A1A] hover:bg-gray-200 transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
              </button>
            </div>

            <div class="relative overflow-hidden bg-[#1A1A1A] rounded-[2.5rem] p-6 text-white shadow-xl">
              <div class="flex items-center gap-5 relative z-10">
                <div class="w-20 h-20 rounded-3xl bg-gradient-to-br from-yellow-400 to-orange-500 p-1 rotate-3 shadow-lg">
                  <div class="w-full h-full bg-[#1A1A1A] rounded-[1.2rem] flex items-center justify-center text-3xl font-black italic -rotate-3">
                    {{ dadosPerfil?.nome ? dadosPerfil.nome.charAt(0) : 'E' }}
                  </div>
                </div>
                <div>
                  <p class="text-2xl font-black italic tracking-tighter">{{ dadosPerfil?.nome || 'Entregador' }}</p>
                  <div class="flex items-center gap-3 mt-1">
                    <span class="flex items-center gap-1 text-yellow-400 font-bold text-sm">⭐ {{ dadosPerfil?.avaliacao || '5.0' }}</span>
                    <span class="h-1 w-1 rounded-full bg-white/20"></span>
                    <span class="text-white/50 text-xs font-bold uppercase tracking-widest">{{ dadosPerfil?.total_entregas || 0 }} Corridas</span>
                  </div>
                </div>
              </div>
              <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div class="bg-gray-50 border border-black/5 rounded-[2rem] p-5">
                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Saldo Total</p>
                <p class="text-2xl font-black text-[#1A1A1A] tracking-tighter">R$ {{ Number(dadosPerfil?.saldo_semana || 0).toFixed(2) }}</p>
              </div>
              <div class="bg-green-50 border border-green-100 rounded-[2rem] p-5">
                <p class="text-[9px] font-black text-green-600/60 uppercase tracking-widest mb-1">Status</p>
                <p class="text-xl font-black text-green-600 uppercase italic tracking-tighter">Ativo</p>
              </div>
            </div>

            <div class="space-y-3">
              <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Equipamento</p>
              <div class="bg-gray-50 rounded-[2.5rem] p-2 border border-black/5 flex items-center gap-2">
                <div class="flex-1 px-4">
                  <label class="text-[8px] font-black text-gray-400 uppercase block">Modelo</label>
                  <input type="text" v-model="nomeMoto" class="bg-transparent w-full font-bold text-sm outline-none text-[#1A1A1A]">
                </div>
                <div class="h-10 w-[1px] bg-gray-200"></div>
                <div class="w-1/3 px-4">
                  <label class="text-[8px] font-black text-gray-400 uppercase block">Placa</label>
                  <input type="text" v-model="placaMoto" class="bg-transparent w-full font-black text-sm outline-none text-[#1A1A1A] uppercase tracking-widest">
                </div>
              </div>
              
              <button 
                @click="salvarDadosVeiculoNoBanco" 
                class="w-full py-5 bg-[#1A1A1A] text-white font-black rounded-[2rem] hover:shadow-2xl active:scale-[0.98] transition-all uppercase tracking-widest text-xs flex items-center justify-center gap-3 shadow-lg"
              >
                <svg v-if="carregandoVeiculo" class="animate-spin h-5 w-5" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                {{ carregandoVeiculo ? 'Atualizando...' : 'Salvar Alterações' }}
              </button>
            </div>

            <button @click="realizarLogout" class="flex items-center justify-center gap-2 text-red-400 font-black uppercase text-[10px] tracking-widest hover:text-red-600 transition-colors py-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
              Encerrar Sessão
            </button>
            
          </div>
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
import api from '../services/api' 

const router = useRouter()

// ✨ DADOS DO PERFIL (Vindos do Banco)
const dadosPerfil = ref({
  nome: 'Carregando...',
  avaliacao: '0.0',
  total_entregas: 0,
  saldo_semana: 0,
  ultimo_repasse: 'Buscando...'
})

const carregandoVeiculo = ref(false)

// 💾 FUNÇÕES DE SALVAMENTO BLINDADO
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

const carregarStatusSeguro = () => {
  const salvo = localStorage.getItem('statusPedido');
  return (salvo && salvo !== 'null' && salvo !== 'undefined') ? salvo : null;
}

// 💾 Variáveis de Estado
const nomeMoto = ref(localStorage.getItem('nomeMoto') || '')
const placaMoto = ref(localStorage.getItem('placaMoto') || '')

const isOnline = ref(localStorage.getItem('isOnline') === 'true')
const statusPedido = ref(carregarStatusSeguro())
const pinCorretoGerado = ref(localStorage.getItem('pinCorretoGerado') || '')

// ✨ VARIÁVEIS PARA A CORRIDA REAL
const corridaAtual = ref(JSON.parse(localStorage.getItem('corridaAtual') || 'null'))
const corridasIgnoradas = ref([]) 
let intervaloBusca = null

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

// Helper para pegar o Header de Autenticação em todas as requisições
const getAuth = () => ({ headers: { Authorization: `Bearer ${localStorage.getItem('token')}` } })

// ... Navegação Maps ...
const prepararNavegacao = (lat, lng) => {
  navDestinoLat.value = lat; navDestinoLng.value = lng; showNavModal.value = true;
}

// ✨ NAVEGAÇÃO BLINDADA (Google Maps e Waze)
const abrirGoogleMaps = () => {
  let query = "";
  
  // Se tivermos as coordenadas salvas no banco (padrão ouro)
  if (navDestinoLat.value && navDestinoLng.value) {
    query = `${navDestinoLat.value},${navDestinoLng.value}`;
  } else {
    // Se não tiver (como no caso da Loja/Coleta), usamos o endereço em texto
    const endereco = corridaAtual.value?.loja_endereco || corridaAtual.value?.endereco;
    query = encodeURIComponent(formatarEndereco(endereco));
  }

  const url = `https://www.google.com/maps/search/?api=1&query=${query}`;
  window.open(url, '_blank');
  showNavModal.value = false;
}

const abrirWaze = () => {
  let ll = "";
  if (navDestinoLat.value && navDestinoLng.value) {
    ll = `${navDestinoLat.value},${navDestinoLng.value}`;
  } else {
    const endereco = corridaAtual.value?.loja_endereco || corridaAtual.value?.endereco;
    ll = encodeURIComponent(formatarEndereco(endereco));
  }

  const url = `https://waze.com/ul?q=${ll}&navigate=yes`;
  window.open(url, '_blank');
  showNavModal.value = false;
}

// ✨ TRADUTOR DE ENDEREÇO BLINDADO EM CASCATA
const buscarCoordenadasDoEndereco = async (enderecoBanco) => {
  if (!enderecoBanco) {
    prepararNavegacao(-7.2016, -39.3182); // Juazeiro Padrão
    return;
  }

  try {
    let tentativas = [];

    // Se for o endereço do cliente (JSON)
    if (enderecoBanco.startsWith('{')) {
      const endObj = JSON.parse(enderecoBanco);
      const rua = endObj.rua || '';
      const num = endObj.numero || '';
      const bairro = endObj.bairro || '';
      const cidade = endObj.cidade || '';
      
      if(cidade) {
        tentativas.push(`${rua}, ${num}, ${bairro}, ${cidade}, Brasil`);
        tentativas.push(`${rua}, ${bairro}, ${cidade}, Brasil`);
        tentativas.push(`${rua}, ${cidade}, Brasil`);
        tentativas.push(`${cidade}, Brasil`); // Se tudo der errado, joga pro centro da cidade!
      } else {
        tentativas.push(`${rua}, ${num}, ${bairro}, Brasil`);
        tentativas.push(`${rua}, ${bairro}, Brasil`);
      }
    } else {
      // Se for o texto da coleta (Loja)
      tentativas.push(`${enderecoBanco}, Juazeiro do Norte, Ceará, Brasil`);
      tentativas.push(`${enderecoBanco}, Brasil`);
      tentativas.push(`Juazeiro do Norte, Ceará, Brasil`); // Fallback se a loja não for mapeada
    }

    for (let query of tentativas) {
      let qLimpa = query.replace(/,\s*,/g, ', ').replace(/\s+/g, ' ').trim();
      try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(qLimpa)}`;
        const response = await fetch(url);
        const data = await response.json();

        if (data && data.length > 0) {
          console.log("📍 Satélite achou a rota por:", qLimpa);
          const lat = parseFloat(data[0].lat);
          const lng = parseFloat(data[0].lon);
          prepararNavegacao(lat, lng);
          return; // Achou? Tira o Entregador daqui e abre o Modal de Rota!
        }
      } catch (error) {}
    }

    // Se REALMENTE esgotar todas as tentativas, cai no centro de Juazeiro para não quebrar a tela
    console.warn("Satélite não mapeou esse local. Rota central ativada.");
    prepararNavegacao(-7.2016, -39.3182);

  } catch (error) {
    prepararNavegacao(-7.2016, -39.3182);
  }
};

// ✨ BUSCAR DADOS INICIAIS (PERFIL, VEÍCULO E CORRIDA ATIVA)
const buscarDadosIniciaisEntregador = async () => {
  try {
    const res = await api.get('/entregador/perfil', getAuth())
    dadosPerfil.value = res.data
    
    // 1. Carrega dados do veículo
    if (res.data.veiculo) {
      nomeMoto.value = res.data.veiculo.modelo || nomeMoto.value
      placaMoto.value = res.data.veiculo.placa || placaMoto.value
      localStorage.setItem('nomeMoto', nomeMoto.value)
      localStorage.setItem('placaMoto', placaMoto.value)
    }

    // 2. ✨ A MÁGICA: Verifica se o Klaus já tem uma corrida vinculada
    if (res.data.pedido_ativo) {
      corridaAtual.value = res.data.pedido_ativo;
      
      // ✨ CORREÇÃO DO BUG DO PIN: Salva o PIN de volta na memória após o F5
      if (res.data.pedido_ativo.codigo_entrega) {
        atualizarPin(res.data.pedido_ativo.codigo_entrega);
      }
      
      const statusBanco = res.data.pedido_ativo.status;
      
      if (statusBanco === 'saiu') {
        statusPedido.value = 'coletado';
        
        // ✨ Usa as coordenadas reais que vieram do banco de dados (se houver)
        if (corridaAtual.value.lat_entrega && corridaAtual.value.lng_entrega) {
          prepararNavegacao(corridaAtual.value.lat_entrega, corridaAtual.value.lng_entrega);
        } else {
          const enderecoCliente = corridaAtual.value.endereco_entrega || corridaAtual.value.endereco;
          if(enderecoCliente) buscarCoordenadasDoEndereco(enderecoCliente);
          else prepararNavegacao(-7.7652, -40.2684);
        }

      } else if (statusBanco === 'perto') {
        statusPedido.value = 'em_rota';
      } else {
        statusPedido.value = 'aceito';
        
        // ✨ Indo coletar (Tenta traçar pra loja)
        if(corridaAtual.value.loja_endereco) buscarCoordenadasDoEndereco(corridaAtual.value.loja_endereco);
        else setTimeout(() => prepararNavegacao(-7.7652, -40.2684), 500);
      }
    }

  } catch (error) {
    console.log("Sessão expirada ou erro ao buscar perfil.");
  }
}

// ✨ SALVAR DADOS DO VEÍCULO
const salvarDadosVeiculoNoBanco = async () => {
  if (!nomeMoto.value || !placaMoto.value) {
    return alert("⚠️ Preencha o modelo e a placa corretamente.")
  }

  carregandoVeiculo.value = true
  try {
    const payload = {
      modelo_veiculo: nomeMoto.value,
      placa_veiculo: placaMoto.value
    }

    await api.put('/entregador/veiculo', payload, getAuth())

    localStorage.setItem('nomeMoto', nomeMoto.value)
    localStorage.setItem('placaMoto', placaMoto.value)

    alert("✅ Veículo atualizado no sistema!")
  } catch (error) {
    console.error("Erro ao atualizar veículo:", error)
    alert("❌ Erro de Autenticação. Faça login novamente.")
  } finally {
    carregandoVeiculo.value = false
  }
}

const salvarCorrida = (corrida) => {
  corridaAtual.value = corrida;
  if (corrida) localStorage.setItem('corridaAtual', JSON.stringify(corrida));
  else localStorage.removeItem('corridaAtual');
}

// ✨ TRADUTOR DE ENDEREÇO (Para tirar o JSON da tela)
const formatarEndereco = (enderecoBanco) => {
  if (!enderecoBanco) return 'Endereço do Cliente';
  try {
    if (enderecoBanco.startsWith('{')) {
      const obj = JSON.parse(enderecoBanco);
      return `${obj.rua}, ${obj.numero} - ${obj.bairro || ''}`;
    }
  } catch (e) {}
  return enderecoBanco;
}

const dadosDaFaseAtual = computed(() => {
  switch(statusPedido.value) {
    case 'aceito': return {
      titulo: 'Indo Coletar', pingColor: 'bg-orange-500', barColor: 'bg-orange-500', iconColor: 'text-orange-500',
      labelInfo: 'Coletar em', infoPrincipal: corridaAtual.value?.loja || 'Loja', infoSecundaria: 'Alerte quando chegar',
      textoBotao: 'Deslize se Coletou'
    }
    case 'coletado': return {
      titulo: 'Pedido Coletado', pingColor: 'bg-indigo-500', barColor: 'bg-indigo-500', iconColor: 'text-indigo-500',
      labelInfo: 'Próximo passo', infoPrincipal: 'Iniciar Viagem', infoSecundaria: 'Tudo pronto na bag?',
      textoBotao: 'Deslize para Iniciar Rota'
    }
    case 'em_rota': return {
      titulo: 'Em Rota de Entrega', pingColor: 'bg-green-500', barColor: 'bg-green-500', iconColor: 'text-green-500',
      labelInfo: 'Entregar em', 
      // ✨ Usa o formatador aqui para ficar bonito:
      infoPrincipal: formatarEndereco(corridaAtual.value?.endereco_entrega || corridaAtual.value?.endereco), 
      infoSecundaria: 'Peça o PIN',
      textoBotao: 'Deslize se Chegou' 
    }
    default: return {}
  }
})

// ✨ BUSCA INVISÍVEL DE CORRIDAS (POLLING)
const iniciarPolling = () => {
  pararPolling();
  intervaloBusca = setInterval(async () => {
    if (isOnline.value && statusPedido.value === null) {
      try {
        const response = await api.get('/entregador/buscar-corrida', getAuth());
        const novaCorrida = response.data;
        
        if (novaCorrida && !corridasIgnoradas.value.includes(novaCorrida.id)) {
          salvarCorrida(novaCorrida);
          atualizarStatus('pendente');
          atualizarPin(novaCorrida.codigo);
          pararPolling(); 
        }
      } catch (error) {
        // Ignora se for 404 (sem corridas)
      }
    }
  }, 10000); 
}

const pararPolling = () => {
  if (intervaloBusca) clearInterval(intervaloBusca);
}

const toggleStatus = () => { 
  atualizarOnline(!isOnline.value);
  if (isOnline.value) {
    iniciarPolling()
  } else {
    atualizarStatus(null);
    swipeProgress.value = 0;
    pararPolling()
  }
}

// 1️⃣ FUNÇÃO PARA O CARD AMARELO (Aceitar Corrida)
const checkSwipeAccept = async () => {
  if (swipeProgress.value > 90) { 
    swipeProgress.value = 100
    try {
      await api.put(`/entregador/aceitar-corrida/${corridaAtual.value.id}`, {}, getAuth());
      
      atualizarStatus('aceito'); 
      swipeProgress.value = 0;
      
      // ✨ Ao aceitar, tenta puxar as coordenadas reais da loja 
      if(corridaAtual.value.loja_endereco) {
        buscarCoordenadasDoEndereco(corridaAtual.value.loja_endereco);
      } else {
        setTimeout(() => prepararNavegacao(-7.7652, -40.2684), 300); // ✨ TRINDADE
      }

    } catch (error) {
      alert("Ops, parece que outro entregador pegou essa corrida primeiro!");
      recusarPedido();
    }
  } else {
    swipeProgress.value = 0
  }
}

const checkSwipeFases = async () => {
  if (swipeFinishProgress.value > 90) {
    swipeFinishProgress.value = 100
    try {
      if (statusPedido.value === 'aceito') {
        // --- FASE: INDO COLETAR NA LOJA ---
        await api.put(`/pedidos/${corridaAtual.value.id}/status`, { status: 'saiu' }, getAuth());
        atualizarStatus('coletado');
        
        // Resetamos as coordenadas para a função abrirGoogleMaps usar o endereço da LOJA
        navDestinoLat.value = null;
        navDestinoLng.value = null;

      } else if (statusPedido.value === 'coletado') {
        // --- FASE: INICIAR ROTA PARA O CLIENTE ---
        atualizarStatus('em_rota');

        // ✨ PEGA O GPS DO CLIENTE QUE SALVOU NO BANCO (ID 46 no seu print)
        if (corridaAtual.value.lat_entrega && corridaAtual.value.lng_entrega) {
          navDestinoLat.value = corridaAtual.value.lat_entrega;
          navDestinoLng.value = corridaAtual.value.lng_entrega;
          showNavModal.value = true; // Abre o modal para escolher Maps ou Waze
        } else {
          // Se for um pedido antigo sem GPS, tenta achar pelo texto
          buscarCoordenadasDoEndereco(corridaAtual.value.endereco_entrega || corridaAtual.value.endereco);
        }

      } else if (statusPedido.value === 'em_rota') {
        await api.put(`/pedidos/${corridaAtual.value.id}/status`, { status: 'perto' }, getAuth());
        showPinModal.value = true;
      }
    } catch (error) {
      alert("Erro ao atualizar status.");
    }
    setTimeout(() => { swipeFinishProgress.value = 0; }, 300)
  } else {
    swipeFinishProgress.value = 0
  }
}

// ✨ CONFIRMAR ENTREGA COM PIN
const confirmarEntrega = async () => {
  if (codigoCliente.value === String(pinCorretoGerado.value)) {
    try {
      await api.put(`/pedidos/${corridaAtual.value.id}/status`, { status: 'entregue' }, getAuth());
      
      alert("✅ Entrega finalizada com sucesso! O dinheiro já está na sua carteira.")
      
      showPinModal.value = false
      atualizarStatus(null);
      atualizarPin('');
      codigoCliente.value = '';
      salvarCorrida(null); 
      
      // ✨ ATUALIZAÇÃO DO SALDO DE FORMA DINÂMICA
      await buscarDadosIniciaisEntregador(); 
      
      iniciarPolling();

    } catch (error) {
      alert("Erro ao finalizar a corrida no servidor.");
    }
  } else {
    alert("⚠️ Código incorreto! O código desta entrega é: " + pinCorretoGerado.value)
  }
}

const recusarPedido = () => {
  if (corridaAtual.value) corridasIgnoradas.value.push(corridaAtual.value.id); 
  atualizarStatus(null);
  salvarCorrida(null);
  swipeProgress.value = 0;
  iniciarPolling(); 
}

const fecharModalPin = () => {
  showPinModal.value = false
  codigoCliente.value = ''
}

const realizarLogout = () => {
  localStorage.removeItem('token');
  localStorage.removeItem('isOnline');
  localStorage.removeItem('statusPedido');
  localStorage.removeItem('pinCorretoGerado');
  localStorage.removeItem('corridaAtual');
  pararPolling();
  router.push('/');
}

onMounted(async () => {
  buscarDadosIniciaisEntregador(); 

  if (isOnline.value && statusPedido.value === null) {
    iniciarPolling();
  }

  setTimeout(() => {
    const mapContainer = document.getElementById('map');
    
    if (mapContainer) {
      // ✨ MAPA INICIAL EM JUAZEIRO DO NORTE (Apenas como ponto de partida)
      map = L.map('map', { zoomControl: false }).setView([-7.2016, -39.3182], 15);
      
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { 
        maxZoom: 19, 
        attribution: '© OpenStreetMap' 
      }).addTo(map);
      
      const motoIcon = L.divIcon({
        className: 'bg-transparent',
        html: `<div class="bg-[#1A1A1A] w-12 h-12 rounded-full border-[3px] border-white shadow-xl flex items-center justify-center text-2xl animate-bounce">🏍️</div>`,
        iconSize: [48, 48], 
        iconAnchor: [24, 48]
      });
      
      // ✨ MARCADOR INICIAL EM JUAZEIRO DO NORTE
      marker = L.marker([-7.2016, -39.3182], { icon: motoIcon }).addTo(map);

      // ✨ A MÁGICA GLOBAL: Lê a antena GPS do dispositivo.
      if ("geolocation" in navigator) {
        navigator.geolocation.watchPosition(
          (position) => {
            const lat = position.coords.latitude;
            const lng = position.coords.longitude;
            console.log("📍 GPS Real do Aparelho encontrado:", lat, lng);
            
            if (marker) marker.setLatLng([lat, lng]);
            if (map) map.setView([lat, lng]); // Move a câmera pro seu local exato
          },
          (error) => { console.error("Erro ao capturar GPS:", error.message); },
          { enableHighAccuracy: true, maximumAge: 0, timeout: 5000 }
        );
      }
    } else {
      console.error("ERRO: Container 'map' não encontrado no DOM.");
    }
  }, 300); 
}); // 

</script>

<style>
.leaflet-control-attribution { display: none !important; }
input[type="range"] { -webkit-appearance: none; background: transparent; }
input[type="range"]::-webkit-slider-thumb { -webkit-appearance: none; height: 80px; width: 80px; background: transparent; cursor: pointer; }
input[type="range"]::-moz-range-thumb { height: 80px; width: 80px; background: transparent; cursor: pointer; border: none; }
</style>