<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] pb-32 md:pb-24 text-[#1A1A1A] relative overflow-x-hidden">
    
<nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-6 md:px-8">
  <div class="flex items-center gap-3">
    <img :src="iRota" alt="Rota Certa" class="w-10 h-10 object-contain drop-shadow-sm">
    <h1 class="text-2xl font-black italic uppercase tracking-tighter">RotaCerta</h1>
  </div>
  
  <div class="hidden md:flex gap-6 items-center">
    <router-link title="Início" to="/home" class="text-2xl hover:scale-110 transition-all opacity-100">
      <Home :size="24" />
    </router-link>
    <router-link title="Meus Pedidos" to="/meus-pedidos" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">
      <ClipboardList :size="24" />
    </router-link>
    <router-link title="Meu Perfil" to="/perfil" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">
      <User :size="24" />
    </router-link>
    <button @click="irParaCarrinho" class="relative text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">
      <ShoppingCart :size="24" />
      <span v-if="totalItensCarrinho > 0" class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold italic shadow-md">
        {{ totalItensCarrinho }}
      </span>
    </button>
  </div>
</nav>

    <div class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 w-[92%] max-w-[400px] bg-[#1A1A1A] rounded-[2rem] shadow-[0_10px_50px_rgba(0,0,0,0.4)] border border-white/10 flex justify-between items-center px-6 py-4 z-50">
      <router-link to="/home" class="flex flex-col items-center gap-1 text-[#C2F2D9]">
        <div class="bg-[#C2F2D9]/20 p-2 rounded-xl transition-all">
          <Home :size="24" />
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Início</span>
      </router-link>
      <router-link to="/meus-pedidos" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2">
          <ClipboardList :size="24" class="opacity-80" />
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Pedidos</span>
      </router-link>
      <button @click="irParaCarrinho" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all relative">
        <div class="p-2 relative">
          <ShoppingCart :size="24" class="opacity-80" />
          <span v-if="totalItensCarrinho > 0" class="absolute top-1 right-0 bg-[#C2F2D9] text-[#1A1A1A] text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black shadow-md">
            {{ totalItensCarrinho }}
          </span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Cesta</span>
      </button>
      <router-link to="/perfil" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2">
          <User :size="24" class="opacity-80" />
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Perfil</span>
      </router-link>
    </div>

    <main class="p-4 md:p-6 lg:p-12 max-w-7xl mx-auto space-y-8 lg:space-y-12">
      
      <section>
        <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar snap-x snap-mandatory">
          <div class="min-w-[280px] lg:min-w-[400px] bg-gradient-to-br from-orange-400 to-red-500 rounded-3xl p-6 text-white shadow-md snap-center relative overflow-hidden flex flex-col justify-center min-h-[140px] lg:min-h-[160px] cursor-pointer hover:shadow-xl transition-all">
             <div class="relative z-10 w-2/3">
               <span class="bg-white/20 px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest backdrop-blur-sm">🔥 Oferta do Dia</span>
               <h4 class="font-black text-xl lg:text-2xl italic leading-tight mt-3 mb-1">Semana do Consumidor</h4>
               <p class="text-xs font-medium opacity-90">Até 40% OFF no app</p>
             </div>
             <div class="absolute -right-4 -bottom-4 opacity-20 transform rotate-12">
               <ShoppingBag :size="96" />
             </div>
          </div>

          <div class="min-w-[280px] lg:min-w-[400px] bg-gradient-to-br from-[#2D4483] to-blue-600 rounded-3xl p-6 text-white shadow-md snap-center relative overflow-hidden flex flex-col justify-center min-h-[140px] lg:min-h-[160px] cursor-pointer hover:shadow-xl transition-all">
             <div class="relative z-10 w-3/4">
               <span class="bg-[#C2F2D9] text-[#1A1A1A] px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest">🧀 O melhor queijo</span>
               <h4 class="font-black text-xl lg:text-2xl italic leading-tight mt-3 mb-1">Laticínios Deleite</h4>
               <p class="text-xs font-medium opacity-90">Compre e ganhe pontos em double!</p>
             </div>
             <div class="absolute -right-2 -bottom-2 opacity-20 transform -rotate-12">
               <Milk :size="96" />
             </div>
          </div>
          
          <div class="min-w-[280px] lg:min-w-[400px] bg-gradient-to-br from-[#1A1A1A] to-gray-800 rounded-3xl p-6 text-white shadow-md snap-center relative overflow-hidden flex flex-col justify-center min-h-[140px] lg:min-h-[160px] cursor-pointer hover:shadow-xl transition-all">
             <div class="relative z-10 w-2/3">
               <span class="bg-white/10 px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest">🛵 RotaCerta Express</span>
               <h4 class="font-black text-xl lg:text-2xl italic leading-tight mt-3 mb-1">Frete Grátis</h4>
               <p class="text-xs font-medium opacity-90">Nas compras acima de R$ 150</p>
             </div>
             <div class="absolute -right-2 -bottom-2 opacity-10">
               <Rocket :size="96" />
             </div>
          </div>
        </div>
      </section>

      <div class="space-y-4">
       <div class="relative group">
          <span class="absolute left-6 top-1/2 -translate-y-1/2 opacity-30">
            <Search :size="24" />
          </span>
          <input v-model="termoBusca" type="text" placeholder="Buscar produtos ou marcas..." class="w-full p-6 pl-16 rounded-[2rem] bg-white shadow-xl shadow-black/5 border-none outline-none focus:ring-4 focus:ring-[#C2F2D9] transition-all font-medium" />
        </div>
        
        <div @click="abrirModalEnderecos" class="flex items-center gap-4 p-4 bg-white/80 backdrop-blur-md rounded-3xl cursor-pointer hover:bg-[#C2F2D9] transition-all w-full md:w-fit border border-black/5 shadow-xl shadow-black/5">
          <MapPin :size="24" class="text-gray-500" />
          <div class="flex-1 text-left">
            <p class="font-black text-xs uppercase tracking-tight">{{ enderecoAtual.titulo }}</p>
            <p class="text-[10px] text-gray-500 font-bold">{{ enderecoAtual.rua }}, {{ enderecoAtual.numero }}</p>
            <p class="text-[10px] text-gray-400 font-medium">{{ enderecoAtual.bairro }} - {{ enderecoAtual.cidade }}</p>
          </div>
          <span class="text-[#2D4483] ml-2 flex items-center gap-1 text-[10px] font-black uppercase tracking-widest">
            Mudar <ArrowRight :size="14" />
          </span>
        </div>
      </div>

      <section>
        <div class="bg-gradient-to-r from-[#1A1A1A] to-[#2D4483] rounded-3xl p-6 lg:p-8 text-white shadow-xl flex justify-between items-center relative overflow-hidden">
          <div class="absolute -right-6 -top-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
          
          <div class="relative z-10">
            <p class="text-[9px] lg:text-[10px] uppercase tracking-widest text-[#C2F2D9] font-black mb-1">Clube RotaCerta</p>
            <div class="flex items-baseline gap-2">
              <span class="text-4xl lg:text-5xl font-black italic tracking-tighter">{{ pontosClube }}</span>              <span class="text-xs lg:text-sm font-bold opacity-80">pontos</span>
            </div>
            <p class="text-[10px] font-medium opacity-60 mt-1">Acumule mais 60 pts para R$ 15 off</p>
          </div>
          
          <button class="relative z-10 bg-[#C2F2D9] text-[#1A1A1A] px-5 py-3 lg:px-8 lg:py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:scale-105 active:scale-95 transition-all">
            Resgatar
          </button>
        </div>
      </section>

      <section>
        <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar">
          <button 
            v-for="cat in categorias" 
            :key="cat" 
            @click="categoriaAtiva = cat"
            :class="categoriaAtiva === cat ? 'bg-[#1A1A1A] text-white border-[#1A1A1A]' : 'bg-white text-[#1A1A1A] border-black/5 hover:bg-gray-50'"
            class="px-8 py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-md transition-all whitespace-nowrap border">
            {{ cat }}
          </button>
        </div>
      </section>

      <h3 class="text-xl font-black uppercase italic tracking-tighter mb-4 pl-2 mt-4 flex items-center gap-2">
        <ShoppingCart :size="24" /> Mercados e Atacados
      </h3>
      <div class="flex gap-6 overflow-x-auto custom-scrollbar pb-4">
        
        <div v-if="lojas.length === 0" class="text-[10px] text-gray-400 font-black uppercase tracking-widest">
          Buscando mercados...
        </div>

        <div 
          v-for="loja in lojas" 
          :key="loja.id" 
          @click="irParaLoja(loja)"
          class="flex flex-col items-center gap-2 relative transition-all"
          :class="loja.aberto ? 'cursor-pointer hover:scale-105' : 'cursor-not-allowed opacity-80'"
        >
          <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm border relative p-1 overflow-hidden" :class="loja.aberto ? 'border-black/5' : 'border-gray-200 grayscale'">
            <img :src="loja.logo" class="w-full h-full object-cover rounded-full" />
          </div>
          
          <div class="flex flex-col items-center">
            <span class="text-[10px] font-black uppercase tracking-widest text-center w-24 truncate" :class="loja.aberto ? 'text-gray-500' : 'text-gray-400 line-through'">
              {{ loja.nome }}
            </span>
            <span v-if="!loja.aberto" class="text-[8px] font-black text-red-500 uppercase tracking-widest mt-0.5">
              Fechado
            </span>
          </div>
        </div>

      </div>

<section v-for="secao in catalogo" :key="secao.titulo">
        <h3 class="text-2xl font-black uppercase italic tracking-tighter mb-6">{{ secao.titulo }}</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 lg:gap-8">
          <div v-for="prod in secao.itens" :key="prod.id" class="relative group">
            <div @click="abrirProduto(prod)" class="bg-white p-4 lg:p-5 rounded-[2rem] lg:rounded-[2.5rem] shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all cursor-pointer border border-black/5 h-full flex flex-col relative overflow-hidden">
              <div class="aspect-square bg-white rounded-2xl lg:rounded-[2rem] mb-4 overflow-hidden flex items-center justify-center p-4">
                <img :src="prod.foto" class="max-h-full max-w-full object-contain transition-transform group-hover:scale-110 duration-500" />
              </div>
              <div class="flex-1">
                <p class="text-[9px] font-black text-[#2D4483] uppercase mb-1">{{ prod.marca }}</p>
                <p class="font-bold text-xs uppercase leading-tight">{{ prod.nome }}</p>
              </div>
              <div class="mt-4 flex flex-col">
                <p v-if="prod.precoAntigo" class="text-[10px] text-red-500 font-bold line-through opacity-60">R$ {{ prod.precoAntigo.toFixed(2) }}</p>
                <p class="text-[#1A1A1A] font-black text-lg lg:text-xl">R$ {{ prod.preco.toFixed(2) }}</p>
              </div>
              
              <div class="absolute bottom-4 right-4 w-8 h-8 bg-white rounded-full shadow-md z-10 flex items-center justify-center border border-black/5 overflow-hidden">
                <img :src="prod.lojaLogo" class="w-full h-full object-cover rounded-full" :title="prod.lojaNome" />
              </div>
            </div>
            <button @click.stop="adicionarRapido(prod)" class="absolute top-3 right-3 w-10 h-10 bg-[#1A1A1A] text-white rounded-full shadow-xl hover:scale-110 active:scale-90 transition-all z-10 flex items-center justify-center">
              <Plus :size="20" />
            </button>
          </div>
        </div>
      </section>
    </main>

    <div v-if="prodSel" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="prodSel = null" class="absolute inset-0 bg-[#1A1A1A]/90 backdrop-blur-md"></div>
      
      <div class="relative bg-white w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-[2rem] lg:rounded-[3.5rem] shadow-2xl flex flex-col md:flex-row animate-in zoom-in duration-300 custom-scrollbar">
        <button @click="prodSel = null" class="absolute top-4 right-4 lg:top-6 lg:right-6 w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200 transition-all flex items-center justify-center">
          <X :size="20" />
        </button>
        
        <div class="md:w-1/2 p-8 lg:p-12 bg-white flex items-center justify-center relative border-b md:border-b-0 md:border-r border-gray-100 min-h-[250px]">
          <img :src="prodSel.foto" class="max-h-60 lg:max-h-80 object-contain" />
        </div>
        
        <div class="md:w-1/2 p-8 lg:p-10 flex flex-col justify-between">
          <div>
            <div class="flex flex-col gap-1 mb-6 pr-12">
               <div class="flex gap-2">
                 <span class="px-3 py-1 bg-[#C2F2D9] text-[#1A1A1A] text-[8px] font-black uppercase rounded-full italic">Em Estoque</span>
                 <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest mt-1">REF: #{{ prodSel.id }}00</span>
               </div>
               <p class="text-[#2D4483] font-black text-xs uppercase mt-4">{{ prodSel.marca }}</p>
               <h2 class="text-2xl lg:text-3xl font-black uppercase italic leading-tight text-[#1A1A1A]">{{ prodSel.nome }}</h2>
            </div>
            
            <div class="flex items-baseline gap-3 mb-4">
               <p v-if="prodSel.precoAntigo" class="text-lg text-red-500 font-bold line-through opacity-50 italic">R$ {{ prodSel.precoAntigo.toFixed(2) }}</p>
               <p class="text-4xl lg:text-5xl font-black italic tracking-tighter text-[#1A1A1A]">R$ {{ prodSel.preco.toFixed(2) }}</p>
            </div>

            <div v-if="prodSel.comparativo && prodSel.comparativo.length > 0" class="mt-4 mb-8 bg-gray-50 rounded-2xl p-4 border border-black/5">
              <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-3">Preços em outras lojas:</p>
              <div class="space-y-2">
                <div v-for="comp in prodSel.comparativo" :key="comp.nome" class="flex justify-between items-center bg-white p-2.5 rounded-xl border border-black/5 hover:border-[#C2F2D9] transition-colors cursor-pointer">
                  <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full overflow-hidden bg-white border border-gray-100 flex items-center justify-center p-0.5">
                      <img :src="comp.logo" class="w-full h-auto object-contain" />
                    </div>
                    <span class="text-[10px] font-bold uppercase">{{ comp.nome }}</span>
                  </div>
                  <div class="flex items-center gap-3">
                    <span class="text-xs font-black text-[#1A1A1A]">R$ {{ comp.preco.toFixed(2) }}</span>
                    <span v-if="comp.preco < prodSel.preco" class="text-[8px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">Mais Barato</span>
                  </div>
                </div>
              </div>
            </div>

          </div>
          <div class="mt-4 lg:mt-6">
            <div class="flex flex-col xl:flex-row gap-4">
              <div class="flex items-center justify-between xl:justify-center gap-4 bg-gray-100 px-6 py-2 xl:py-0 rounded-2xl border border-black/5 font-black">
                <button @click="qtdModal > 1 ? qtdModal-- : null" class="flex items-center justify-center p-2">
                  <Minus :size="20" />
                </button>
                <span class="text-lg w-6 text-center">{{ qtdModal }}</span>
                <button @click="qtdModal++" class="flex items-center justify-center p-2">
                  <Plus :size="20" />
                </button>
              </div>
              <button @click="adicionarPeloModal" class="w-full py-4 lg:py-5 bg-[#1A1A1A] text-white font-black rounded-2xl uppercase tracking-widest shadow-xl hover:bg-black transition-all">
                Adicionar
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="modalEnderecosAberto" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModalEnderecos" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300 max-h-[90vh] overflow-y-auto custom-scrollbar">
        <button @click="fecharModalEnderecos" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200 flex items-center justify-center">
          <X :size="20" />
        </button>
        
        <div class="mb-8">
          <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-1">Endereços</h2>
          <p class="text-gray-500 text-xs font-medium">Selecione onde vamos entregar suas compras</p>
        </div>

        <div v-if="!mostrandoFormEndereco" class="space-y-4">
          <div 
            v-for="(end, index) in enderecosSalvos" :key="index" 
            @click="selecionarEndereco(end)"
            class="flex items-start gap-4 p-4 rounded-2xl border-2 transition-all cursor-pointer relative group"
            :class="enderecoAtual.titulo === end.titulo ? 'border-[#1A1A1A] bg-gray-50' : 'border-transparent bg-white shadow-sm hover:border-black/10 hover:shadow-md'"
          >
            <div class="flex-shrink-0">
              <MapPin :size="24" />
            </div>
            <div class="flex-1">
              <p class="font-black text-xs uppercase">{{ end.titulo }}</p>
              <p class="text-[11px] text-gray-500 mt-1">{{ end.rua }}, {{ end.numero }}</p>
              <p class="text-[11px] text-gray-500">{{ end.bairro }} - {{ end.cidade }}</p>
            </div>
            <div v-if="enderecoAtual.titulo === end.titulo" class="w-6 h-6 bg-[#1A1A1A] text-[#C2F2D9] rounded-full flex items-center justify-center text-xs font-black">
              ✓
            </div>
          </div>

          <button @click="mostrandoFormEndereco = true" class="w-full py-5 border-2 border-dashed border-gray-200 rounded-2xl text-gray-400 font-black uppercase tracking-widest text-xs hover:border-[#1A1A1A] hover:text-[#1A1A1A] transition-all">
            + Adicionar Novo Endereço
          </button>
        </div>

        <form v-if="mostrandoFormEndereco" @submit.prevent="salvarEndereco" class="space-y-4">
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
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Bairro</label>
            <input v-model="novoEndereco.bairro" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Cidade - Estado (Ex: PE)</label>
            <input v-model="novoEndereco.cidade" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <button type="button" @click="mostrandoFormEndereco = false" class="py-4 font-black uppercase text-[10px] tracking-widest text-gray-400 hover:text-[#1A1A1A]">Cancelar</button>
            <button type="submit" class="bg-[#1A1A1A] text-[#C2F2D9] py-4 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-black">Salvar</button>
          </div>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import iRota from '../assets/iRota.png'
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api' 
import { 
  Home, 
  ClipboardList, 
  User, 
  ShoppingCart, 
  ShoppingBag, 
  Milk, 
  Rocket, 
  Search, 
  MapPin, 
  ArrowRight, 
  Plus, 
  Minus, 
  X, 
  Star, 
  Apple, 
  Beef, 
  Sparkles, 
  Package, 
  CupSoda, 
  Croissant 
} from 'lucide-vue-next'

const router = useRouter()

// === VARIÁVEIS DE ESTADO ===
const modalEnderecosAberto = ref(false)
const mostrandoFormEndereco = ref(false)
const prodSel = ref(null)
const qtdModal = ref(1)

// === DADOS REATIVOS ===
const enderecosSalvos = ref([])
// ✨ NOVO: Bairro e Cidade adicionados para ficar padrão com o banco
const novoEndereco = ref({ titulo: '', cep: '', numero: '', rua: '', bairro: '', cidade: '' })
const enderecoAtual = ref({ titulo: 'Carregando...', rua: 'Buscando endereço', numero: '', bairro: 'Carregando...', cidade: 'Carregando...' })
const pontosClube = ref(0)

const lojas = ref([])
const produtosOriginais = ref([]) 
const termoBusca = ref('') 
const categoriaAtiva = ref('⭐ Todas') 
const categorias = ['⭐ Todas', '🍏 Hortifruti', '🥩 Açougue', '🧼 Limpeza', '📦 Mercearia', '🥤 Bebidas', '🍞 Padaria', '🧀 Laticínios']

// === CATÁLOGO INTELIGENTE ===
const catalogo = computed(() => {
  let filtrados = produtosOriginais.value

  if (termoBusca.value) {
    const termo = termoBusca.value.toLowerCase()
    filtrados = filtrados.filter(p => 
      p.nome.toLowerCase().includes(termo) || 
      p.marca.toLowerCase().includes(termo)
    )
  }

  if (categoriaAtiva.value !== '⭐ Todas') {
    const nomeCat = categoriaAtiva.value.split(' ')[1] 
    filtrados = filtrados.filter(p => p.categoria && p.categoria.includes(nomeCat))
  }

  if (filtrados.length === 0) {
    return [{ titulo: 'Nenhum produto encontrado 😕', itens: [] }]
  }

  return [{
    titulo: termoBusca.value ? 'Resultados da Busca' : (categoriaAtiva.value !== '⭐ Todas' ? categoriaAtiva.value : '🔥 Novidades e Ofertas'),
    itens: filtrados
  }]
})

// === ESTADO DO CARRINHO ===
const carrinho = ref(JSON.parse(localStorage.getItem('carrinho') || '[]'))

const totalItensCarrinho = computed(() => {
  if (!Array.isArray(carrinho.value)) return 0
  return carrinho.value.reduce((total, item) => total + item.quantidade, 0)
})

// === FUNÇÕES DE ENDEREÇO ===
const abrirModalEnderecos = () => { modalEnderecosAberto.value = true; mostrandoFormEndereco.value = false }
const fecharModalEnderecos = () => { modalEnderecosAberto.value = false }
const selecionarEndereco = (end) => { enderecoAtual.value = end; fecharModalEnderecos() }

const salvarEndereco = async () => {
  try {
    const payload = {
      nome_local: novoEndereco.value.titulo, // Para o banco entender
      cep: novoEndereco.value.cep,
      numero: novoEndereco.value.numero,
      rua: novoEndereco.value.rua,
      bairro: novoEndereco.value.bairro, 
      cidade: novoEndereco.value.cidade
    }

    const response = await api.post('/enderecos', payload)
    const endBanco = response.data.endereco

    const endFormatado = {
      ...endBanco,
      titulo: endBanco.nome_local, 
      bairro: endBanco.bairro,
      cidade: endBanco.cidade
    }

    enderecosSalvos.value.push(endFormatado)
    enderecoAtual.value = endFormatado
    
    mostrandoFormEndereco.value = false
    fecharModalEnderecos()
    novoEndereco.value = { titulo: '', cep: '', numero: '', rua: '', bairro: '', cidade: '' }
    
  } catch (error) {
    console.error("Erro ao salvar endereço:", error)
    alert("Não foi possível salvar o endereço. Verifique os dados.")
  }
}

// ✨ FUNÇÃO BLINDADA PARA LER IMAGENS (AGORA COM SUPORTE A BASE64)
const getImageUrl = (caminho, fallback) => {
  if (!caminho || caminho === 'null' || caminho === 'undefined') return fallback;
  
  const pathString = String(caminho);

  // 🛑 O SEGREDO ESTÁ AQUI: Se for link externo OU código Base64, devolve intacto!
  if (pathString.startsWith('http') || pathString.startsWith('data:image')) {
    return pathString; 
  }

  // Apenas se for um caminho de pasta (ex: lojas/foto.png), ele monta a URL do storage
  let cleanPath = pathString.replace(/^\/+/, '').replace(/^public\//, '').replace(/^storage\//, '');
  return `http://localhost:8000/storage/${cleanPath}`;
};

// === CARREGAMENTO INICIAL (API) ===
const carregarDadosDaHome = async () => {
  try {
    const [resLojas, resProdutos, resPontos, resEnderecos] = await Promise.all([
      api.get('/lojas-parceiras'), 
      api.get('/produtos'),
      api.get('/meus-pontos'),
      api.get('/enderecos')
    ])

    // 1. Mapeando as Lojas usando o getImageUrl
    lojas.value = resLojas.data.map(loja => {
      return {
        id: loja.id,
        nome: loja.nome_loja || 'Mercado Parceiro',
        logo: getImageUrl(loja.logo_loja, 'https://cdn-icons-png.flaticon.com/512/1384/1384063.png'), 
        aberto: loja.aberto == 1 || loja.aberto === true || loja.aberto === "1" 
      }
    })
    
    // 2. Mapeando Produtos e recuperando as fotos com o getImageUrl
    produtosOriginais.value = resProdutos.data.map(prod => {
      const lojaDoProduto = lojas.value.find(l => l.id === prod.lojista_id)
      
      const logoDaLoja = lojaDoProduto ? lojaDoProduto.logo : 'https://cdn-icons-png.flaticon.com/512/1384/1384063.png'
      const taAberto = lojaDoProduto ? lojaDoProduto.aberto : true

      return {
        id: prod.id,
        nome: prod.nome,
        marca: prod.marca || 'Diversos', 
        categoria: prod.categoria || '', 
        preco: Number(prod.preco) || 0,
        precoAntigo: null,
        // Usamos a função de blindagem aqui também!
        foto: getImageUrl(prod.imagem_url, 'https://via.placeholder.com/150'),
        lojaNome: lojaDoProduto ? lojaDoProduto.nome : 'RotaCerta',
        lojista_id: prod.lojista_id,
        lojaLogo: logoDaLoja,
        lojaAberta: taAberto 
      }
    })

    pontosClube.value = resPontos.data.pontos || 0

    if (resEnderecos.data.length > 0) {
      enderecosSalvos.value = resEnderecos.data.map(end => ({
        ...end,
        titulo: end.nome_local, 
        bairro: end.bairro || 'Sem Bairro',
        cidade: end.cidade || 'Sem Cidade'
      }))
      enderecoAtual.value = enderecosSalvos.value[0] 
    } else {
      enderecoAtual.value = { titulo: 'Sem endereço', rua: 'Clique para adicionar', numero: '' }
    }

  } catch (error) {
    console.error("Deu ruim ao buscar os dados do Back-end:", error)
  }
}
onMounted(() => {
  carregarDadosDaHome()
})

// === INTERAÇÃO COM LOJAS (Círculos no topo) ===
const irParaLoja = (loja) => {
  if (!loja.aberto) {
    return alert("⚠️ Esta loja está fechada no momento! Volte mais tarde.")
  }
  alert(`Exibindo ofertas de: ${loja.nome}`)
}

// === FUNÇÕES DE PRODUTO E CARRINHO ===
const abrirProduto = (p) => { 
  if (!p.lojaAberta) {
    return alert("⚠️ Esta loja está fechada! Não é possível visualizar detalhes no momento.")
  }

  const concorrentes = lojas.value
    .filter(l => l.nome !== p.lojaNome && l.aberto) // Mostra concorrentes abertos
    .map((l, index) => {
      const variacao = index === 0 ? -0.25 : (index === 1 ? 0.40 : 0.15);
      return { nome: l.nome, logo: l.logo, preco: p.preco + variacao }
    })
    .sort((a, b) => a.preco - b.preco);

  prodSel.value = { ...p, comparativo: concorrentes };
  qtdModal.value = 1;
}

const salvarCarrinho = () => {
  localStorage.setItem('carrinho', JSON.stringify(carrinho.value))
}

const adicionarAoCarrinho = (produto, quantidade) => {
  // BLOQUEIO CRÍTICO DE CARRINHO
  if (produto.lojaAberta === false || produto.lojaAberta === 0) {
    return alert("🛑 Operação bloqueada. O estabelecimento encontra-se fechado.")
  }

  const itemExistente = carrinho.value.find(item => item.id === produto.id)
  
  if (itemExistente) {
    itemExistente.quantidade += quantidade
  } else {
    carrinho.value.push({ ...produto, quantidade: quantidade })
  }
  
  salvarCarrinho()
}

const adicionarPeloModal = () => { 
  adicionarAoCarrinho(prodSel.value, qtdModal.value)
  prodSel.value = null 
}

const adicionarRapido = (p) => { 
  adicionarAoCarrinho(p, 1) 
}

const irParaCarrinho = () => { router.push('/carrinho') }
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
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #94A3B8;
}
</style>