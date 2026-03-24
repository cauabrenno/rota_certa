<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] pb-32 md:pb-24 text-[#1A1A1A] relative">
    
    <nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-6 md:px-8">
      <div class="flex items-center gap-2">
        <span class="text-3xl">⚡</span>
        <h1 class="text-2xl font-black italic uppercase tracking-tighter">RotaCerta</h1>
      </div>
      
      <div class="hidden md:flex gap-6 items-center">
        <router-link title="Meus Pedidos" to="/meus-pedidos" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">📄</router-link>
        <router-link title="Meu Perfil" to="/perfil" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">👤</router-link>
        <button @click="irParaCarrinho" class="relative text-2xl hover:scale-110 transition-all">
          🛒
          <span class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold italic shadow-md">3</span>
        </button>
      </div>
    </nav>

    <div class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 w-[92%] max-w-[400px] bg-[#1A1A1A] rounded-[2rem] shadow-[0_10px_50px_rgba(0,0,0,0.4)] border border-white/10 flex justify-between items-center px-6 py-4 z-50">
      <router-link to="/home" class="flex flex-col items-center gap-1 text-[#C2F2D9]">
        <div class="bg-[#C2F2D9]/20 p-2 rounded-xl transition-all">
          <span class="text-2xl">🏠</span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Início</span>
      </router-link>
      <router-link to="/meus-pedidos" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2">
          <span class="text-2xl grayscale opacity-80">📄</span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Pedidos</span>
      </router-link>
      <button @click="irParaCarrinho" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all relative">
        <div class="p-2 relative">
          <span class="text-2xl grayscale opacity-80">🛒</span>
          <span class="absolute top-1 right-0 bg-[#C2F2D9] text-[#1A1A1A] text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black shadow-md">3</span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Cesta</span>
      </button>
      <router-link to="/perfil" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2">
          <span class="text-2xl grayscale opacity-80">👤</span>
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
             <div class="absolute -right-4 -bottom-4 text-7xl lg:text-8xl opacity-20 transform rotate-12">🛍️</div>
          </div>

          <div class="min-w-[280px] lg:min-w-[400px] bg-gradient-to-br from-[#2D4483] to-blue-600 rounded-3xl p-6 text-white shadow-md snap-center relative overflow-hidden flex flex-col justify-center min-h-[140px] lg:min-h-[160px] cursor-pointer hover:shadow-xl transition-all">
             <div class="relative z-10 w-3/4">
               <span class="bg-[#C2F2D9] text-[#1A1A1A] px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest">🧀 O melhor queijo</span>
               <h4 class="font-black text-xl lg:text-2xl italic leading-tight mt-3 mb-1">Laticínios Deleite</h4>
               <p class="text-xs font-medium opacity-90">Compre e ganhe pontos em dobro!</p>
             </div>
             <div class="absolute -right-2 -bottom-2 text-7xl lg:text-8xl opacity-20 transform -rotate-12">🧀</div>
          </div>
          
          <div class="min-w-[280px] lg:min-w-[400px] bg-gradient-to-br from-[#1A1A1A] to-gray-800 rounded-3xl p-6 text-white shadow-md snap-center relative overflow-hidden flex flex-col justify-center min-h-[140px] lg:min-h-[160px] cursor-pointer hover:shadow-xl transition-all">
             <div class="relative z-10 w-2/3">
               <span class="bg-white/10 px-3 py-1.5 rounded-full text-[8px] font-black uppercase tracking-widest">🛵 RotaCerta Express</span>
               <h4 class="font-black text-xl lg:text-2xl italic leading-tight mt-3 mb-1">Frete Grátis</h4>
               <p class="text-xs font-medium opacity-90">Nas compras acima de R$ 150</p>
             </div>
             <div class="absolute -right-2 -bottom-2 text-7xl lg:text-8xl opacity-10">🚀</div>
          </div>

        </div>
      </section>

      <div class="space-y-4">
        <div class="relative group">
          <span class="absolute left-6 top-1/2 -translate-y-1/2 text-xl opacity-30">🔍</span>
          <input type="text" placeholder="Buscar produtos ou mercados..." class="w-full p-6 pl-16 rounded-[2rem] bg-white shadow-xl shadow-black/5 border-none outline-none focus:ring-4 focus:ring-[#C2F2D9] transition-all font-medium" />
        </div>
        <div @click="trocarEndereco" class="flex items-center gap-3 px-6 py-3 bg-white/60 backdrop-blur-sm rounded-2xl cursor-pointer hover:bg-[#C2F2D9] transition-all w-fit border border-black/5 shadow-sm">
          <span class="text-lg">📍</span>
          <p class="text-sm font-black">{{ enderecoAtual }} <span class="text-[#2D4483] ml-2">Mudar ➔</span></p>
        </div>
      </div>

      <section>
        <div class="bg-gradient-to-r from-[#1A1A1A] to-[#2D4483] rounded-3xl p-6 lg:p-8 text-white shadow-xl flex justify-between items-center relative overflow-hidden">
          <div class="absolute -right-6 -top-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
          
          <div class="relative z-10">
            <p class="text-[9px] lg:text-[10px] uppercase tracking-widest text-[#C2F2D9] font-black mb-1">Clube RotaCerta</p>
            <div class="flex items-baseline gap-2">
              <span class="text-4xl lg:text-5xl font-black italic tracking-tighter">340</span>
              <span class="text-xs lg:text-sm font-bold opacity-80">pontos</span>
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
          <button v-for="cat in categorias" :key="cat" class="px-8 py-4 bg-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-md hover:bg-[#1A1A1A] hover:text-white transition-all whitespace-nowrap border border-black/5">
            {{ cat }}
          </button>
        </div>
      </section>

      <section>
        <h3 class="text-xl font-black mb-6 uppercase italic tracking-tighter">Mercados e Atacados</h3>
        <div class="flex gap-8 overflow-x-auto pb-6 custom-scrollbar">
          <div v-for="loja in lojas" :key="loja.nome" class="min-w-[110px] text-center group cursor-pointer">
            <div class="w-24 h-24 bg-white rounded-full shadow-lg border-4 border-transparent group-hover:border-[#C2F2D9] flex items-center justify-center p-4 transition-all mx-auto overflow-hidden">
              <img :src="loja.logo" class="w-full h-auto transition-transform duration-300 group-hover:scale-110 object-contain" />
            </div>
            <p class="mt-3 font-black text-[9px] uppercase tracking-widest opacity-60 italic">{{ loja.nome }}</p>
          </div>
        </div>
      </section>

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
              
              <div class="absolute bottom-4 right-4 w-8 h-8 bg-white rounded-full shadow-md z-10 flex items-center justify-center p-1 border border-black/5 overflow-hidden">
                <img :src="prod.lojaLogo" class="w-full h-auto object-contain" :title="prod.lojaNome" />
              </div>
            </div>
            <button @click.stop="adicionarRapido(prod)" class="absolute top-3 right-3 w-10 h-10 bg-[#1A1A1A] text-white rounded-full shadow-xl hover:scale-110 active:scale-90 transition-all z-10 flex items-center justify-center">
              <span class="text-xl font-light leading-none mb-1">+</span>
            </button>
          </div>
        </div>
      </section>
    </main>

    <div v-if="prodSel" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="prodSel = null" class="absolute inset-0 bg-[#1A1A1A]/90 backdrop-blur-md"></div>
      
      <div class="relative bg-white w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-[2rem] lg:rounded-[3.5rem] shadow-2xl flex flex-col md:flex-row animate-in zoom-in duration-300 custom-scrollbar">
        <button @click="prodSel = null" class="absolute top-4 right-4 lg:top-6 lg:right-6 w-10 h-10 lg:w-12 lg:h-12 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200 transition-all flex items-center justify-center">✕</button>
        
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
            <p class="text-gray-500 text-xs font-medium leading-relaxed">Produto selecionado com rigoroso controle de qualidade. Garantia de frescor e procedência {{ prodSel.lojaNome }}.</p>
          </div>
          <div class="mt-8 lg:mt-10">
            <div class="flex items-baseline gap-3 mb-6">
               <p v-if="prodSel.precoAntigo" class="text-lg text-red-500 font-bold line-through opacity-50 italic">R$ {{ prodSel.precoAntigo.toFixed(2) }}</p>
               <p class="text-4xl lg:text-5xl font-black italic tracking-tighter text-[#1A1A1A]">R$ {{ prodSel.preco.toFixed(2) }}</p>
            </div>
            <div class="flex flex-col xl:flex-row gap-4">
              <div class="flex items-center justify-between xl:justify-center gap-4 bg-gray-100 px-6 py-2 xl:py-0 rounded-2xl border border-black/5 font-black">
                <button @click="qtdModal > 1 ? qtdModal-- : null" class="text-2xl px-2">-</button>
                <span class="text-lg w-6 text-center">{{ qtdModal }}</span>
                <button @click="qtdModal++" class="text-2xl px-2">+</button>
              </div>
              <button @click="adicionarPeloModal" class="w-full py-4 lg:py-5 bg-[#1A1A1A] text-white font-black rounded-2xl uppercase tracking-widest shadow-xl hover:bg-black transition-all">
                Adicionar
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
import { useRouter } from 'vue-router'

import logoMix from '../assets/logoMix.png'
import novoAtacarejo from '../assets/novoAtacarejo.jpg'
import assai from '../assets/assai.png'
import atacadao from '../assets/atacadao.png'

import arrozBranco from '../assets/arrozBranco.jpg'
import acucarUniao from '../assets/acucarUniao.jpg'
import amaciante from '../assets/amaciante.jpg'
import cafeSanta from '../assets/cafeSanta.png'
import feijaoCarioca from '../assets/feijaoCarioca.jpg'
import oleoSoja from '../assets/oleoSoja.jpg'
import omo from '../assets/OMO.jpg'
import papelNeve from '../assets/papelNeve.jpg'
import ype from '../assets/ype.jpg'

const router = useRouter()
const enderecoAtual = ref('Rua das Flores, 123')
const prodSel = ref(null)
const qtdModal = ref(1)

const categorias = ['🍏 Hortifruti', '🥩 Açougue', '🧼 Limpeza', '📦 Mercearia', '🥤 Bebidas', '🍞 Padaria']

const lojas = ref([
  { nome: 'Mix Mateus', logo: logoMix },
  { nome: 'Novo Atacarejo', logo: novoAtacarejo },
  { nome: 'Assaí', logo: assai },
  { nome: 'Atacadão', logo: atacadao }
])

const catalogo = ref([
  {
    titulo: '🔥 Super Ofertas',
    itens: [
      { id: 1, nome: 'Arroz Branco 1kg', marca: 'Camil', preco: 5.89, precoAntigo: 7.50, foto: arrozBranco, lojaNome: 'Mix Mateus', lojaLogo: logoMix },
      { id: 2, nome: 'Açúcar União 1kg', marca: 'União', preco: 4.20, precoAntigo: 5.10, foto: acucarUniao, lojaNome: 'Atacadão', lojaLogo: atacadao },
      { id: 3, nome: 'Feijão Carioca 1kg', marca: 'Kicaldo', preco: 8.90, precoAntigo: 10.50, foto: feijaoCarioca, lojaNome: 'Novo Atacarejo', lojaLogo: novoAtacarejo },
      { id: 4, nome: 'Óleo de Soja 900ml', marca: 'Soya', preco: 6.45, precoAntigo: 7.20, foto: oleoSoja, lojaNome: 'Assaí', lojaLogo: assai },
      { id: 5, nome: 'Café Santa Clara 250g', marca: 'Santa Clara', preco: 11.90, precoAntigo: 14.00, foto: cafeSanta, lojaNome: 'Mix Mateus', lojaLogo: logoMix },
    ]
  },
  {
    titulo: '🧼 Higiene e Limpeza',
    itens: [
      { id: 6, nome: 'Detergente Ypê 500ml', marca: 'Ypê', preco: 2.39, precoAntigo: null, foto: ype, lojaNome: 'Atacadão', lojaLogo: atacadao },
      { id: 7, nome: 'Sabão Omo 800g', marca: 'Omo', preco: 16.50, precoAntigo: 18.90, foto: omo, lojaNome: 'Assaí', lojaLogo: assai },
      { id: 8, nome: 'Amaciante Downy', marca: 'Downy', preco: 19.90, precoAntigo: 22.50, foto: amaciante, lojaNome: 'Novo Atacarejo', lojaLogo: novoAtacarejo },
      { id: 9, nome: 'Papel Neve 12un', marca: 'Neve', preco: 22.00, precoAntigo: 25.00, foto: papelNeve, lojaNome: 'Mix Mateus', lojaLogo: logoMix },
    ]
  }
])

const abrirProduto = (p) => { prodSel.value = p; qtdModal.value = 1 }
const adicionarPeloModal = () => { alert(`${qtdModal.value}x ${prodSel.value.nome} adicionados!`); prodSel.value = null }
const adicionarRapido = (p) => { alert(`${p.nome} adicionado!`) }
const irParaCarrinho = () => { router.push('/carrinho') }
const trocarEndereco = () => { const n = prompt("Novo endereço:"); if(n) enderecoAtual.value = n }
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