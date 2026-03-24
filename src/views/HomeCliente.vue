<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] pb-24 text-[#1A1A1A]">
    
    <nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-8">
      <div class="flex items-center gap-2">
        <span class="text-3xl">⚡</span>
        <h1 class="text-2xl font-black italic uppercase tracking-tighter">RotaCerta</h1>
      </div>
      <div class="flex gap-6 items-center">
        <router-link to="/perfil" class="text-2xl hover:scale-110 transition-all">👤</router-link>
        <button @click="irParaCarrinho" class="relative text-2xl hover:scale-110 transition-all">
          🛒
          <span class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold">2</span>
        </button>
      </div>
    </nav>

    <main class="p-6 lg:p-12 max-w-7xl mx-auto space-y-12">
      
      <div class="space-y-4">
        <div class="relative group">
          <span class="absolute left-6 top-1/2 -translate-y-1/2 text-xl opacity-30">🔍</span>
          <input 
            type="text" 
            placeholder="O que vamos pedir hoje?" 
            class="w-full p-6 pl-16 rounded-[2rem] bg-white shadow-xl shadow-black/5 border-none outline-none focus:ring-4 focus:ring-[#C2F2D9] transition-all font-medium"
          />
        </div>

        <div @click="trocarEndereco" class="flex items-center gap-3 px-6 py-3 bg-white/60 backdrop-blur-sm rounded-2xl cursor-pointer hover:bg-[#C2F2D9] transition-all w-fit border border-black/5 shadow-sm">
          <span class="text-lg">📍</span>
          <div>
            <p class="text-[10px] font-black uppercase opacity-40 leading-none">Entregar em:</p>
            <p class="text-sm font-black">{{ enderecoAtual }} <span class="text-[#2D4483] ml-2">Mudar ➔</span></p>
          </div>
        </div>
      </div>

      <section>
        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
          <button v-for="cat in categorias" :key="cat" class="px-8 py-4 bg-white rounded-2xl font-black uppercase text-xs tracking-widest shadow-md hover:bg-[#1A1A1A] hover:text-white transition-all whitespace-nowrap border border-black/5">
            {{ cat }}
          </button>
        </div>
      </section>

      <section>
        <h3 class="text-xl font-black mb-6 uppercase italic tracking-tighter">Lojas em Destaque</h3>
        <div class="flex gap-8 overflow-x-auto pb-6 scrollbar-hide">
          <div v-for="n in 4" :key="n" class="min-w-[120px] text-center group cursor-pointer">
            <div class="w-24 h-24 bg-white rounded-full shadow-lg border-4 border-transparent group-hover:border-[#C2F2D9] flex items-center justify-center p-4 transition-all mx-auto">
              <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Logo_Mix_Mateus.png" class="w-full h-auto grayscale group-hover:grayscale-0 transition-all" />
            </div>
            <p class="mt-3 font-black text-[10px] uppercase tracking-widest opacity-60">Mix Mateus</p>
          </div>
        </div>
      </section>

      <section>
        <h3 class="text-2xl font-black mb-8 uppercase italic tracking-tighter">🔥 Mais Pedidos</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
          <div v-for="n in 5" :key="n" class="relative group">
            <div @click="abrirProduto(n)" class="bg-white p-5 rounded-[2.5rem] shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all cursor-pointer border border-black/5 h-full">
              <div class="aspect-square bg-[#F1F5F9] rounded-[2rem] mb-4 overflow-hidden">
                <img src="https://img.freepik.com/fotos-gratis/hamburguer-de-carne-com-queijo-e-salada-fresca-em-fundo-isolado_185193-54150.jpg" class="w-full h-full object-cover" />
              </div>
              <p class="font-black text-sm uppercase leading-tight">Burger Prime #{{n}}</p>
              <p class="text-[#2D4483] font-black mt-2 text-lg">R$ 32,90</p>
            </div>

            <button 
              @click.stop="adicionarRapido(n)"
              class="absolute top-4 right-4 w-12 h-12 bg-[#1A1A1A] text-white rounded-full shadow-2xl hover:scale-110 active:scale-90 transition-all z-10 flex items-center justify-center"
            >
              <span class="text-2xl font-light leading-none mb-1">+</span>
            </button>
          </div>
        </div>
      </section>
    </main>

    <div v-if="produtoSelecionado" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="produtoSelecionado = null" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-md"></div>
      <div class="relative bg-white w-full max-w-xl rounded-[3rem] overflow-hidden shadow-2xl">
        <button @click="produtoSelecionado = null" class="absolute top-6 right-6 w-10 h-10 bg-black/5 rounded-full font-bold">✕</button>
        <div class="h-64 bg-gray-200">
           <img src="https://img.freepik.com/fotos-gratis/hamburguer-de-carne-com-queijo-e-salada-fresca-em-fundo-isolado_185193-54150.jpg" class="w-full h-full object-cover" />
        </div>
        <div class="p-10">
          <h2 class="text-3xl font-black uppercase italic">Burger Prime Especial</h2>
          <p class="text-[#1A1A1A]/60 mt-4 font-medium leading-relaxed">Pão brioche, blend 180g e queijo cheddar.</p>
          <div class="mt-10 flex items-center justify-between">
            <span class="text-3xl font-black text-[#2D4483]">R$ 32,90</span>
            <button @click="produtoSelecionado = null" class="px-10 py-5 bg-[#1A1A1A] text-white font-black rounded-2xl uppercase tracking-widest shadow-xl">Adicionar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const enderecoAtual = ref('Sua Localização Atual')
const produtoSelecionado = ref(null)
const categorias = ['🍔 Hambúrgueres', '🍕 Pizzas', '🥤 Bebidas', '🍟 Porções', '🍦 Sobremesas']

const abrirProduto = (id) => { produtoSelecionado.value = id }
const adicionarRapido = (id) => { alert(`Adicionado ao carrinho!`) }
const trocarEndereco = () => { 
  const novo = prompt("Para onde vamos enviar?")
  if (novo) enderecoAtual.value = novo
}
const irParaCarrinho = () => { router.push('/carrinho') }
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>