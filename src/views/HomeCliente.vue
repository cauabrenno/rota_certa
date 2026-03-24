<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F1F7] pb-24 text-[#1A1A1A]">
    
    <nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-8">
      <div class="flex items-center gap-2">
        <span class="text-3xl">🛒</span>
        <h1 class="text-2xl font-black italic uppercase tracking-tighter">RotaCerta</h1>
      </div>
      <div class="flex gap-6 items-center">
        <router-link to="/perfil" class="text-2xl hover:scale-110 transition-all">👤</router-link>
        <button @click="irParaCarrinho" class="relative text-2xl hover:scale-110 transition-all">
          📦
          <span class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold italic">3</span>
        </button>
      </div>
    </nav>

    <main class="p-6 lg:p-12 max-w-7xl mx-auto space-y-12">
      
      <div class="space-y-4">
        <div class="relative group">
          <span class="absolute left-6 top-1/2 -translate-y-1/2 text-xl opacity-30">🔍</span>
          <input 
            type="text" 
            placeholder="O que você precisa comprar hoje?" 
            class="w-full p-6 pl-16 rounded-[2rem] bg-white shadow-xl shadow-black/5 border-none outline-none focus:ring-4 focus:ring-[#C2F2D9] transition-all font-medium"
          />
        </div>

        <div @click="trocarEndereco" class="flex items-center gap-3 px-6 py-3 bg-white/60 backdrop-blur-sm rounded-2xl cursor-pointer hover:bg-[#C2F2D9] transition-all w-fit border border-black/5 shadow-sm">
          <span class="text-lg">📍</span>
          <div>
            <p class="text-[10px] font-black uppercase opacity-40 leading-none font-bold">Entregar em:</p>
            <p class="text-sm font-black">{{ enderecoAtual }} <span class="text-[#2D4483] ml-2">Mudar ➔</span></p>
          </div>
        </div>
      </div>

      <section>
        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
          <button v-for="cat in categorias" :key="cat" class="px-8 py-4 bg-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-md hover:bg-[#1A1A1A] hover:text-white transition-all whitespace-nowrap border border-black/5">
            {{ cat }}
          </button>
        </div>
      </section>

      <section>
        <h3 class="text-xl font-black mb-6 uppercase italic tracking-tighter">Mercados e Atacados</h3>
        <div class="flex gap-8 overflow-x-auto pb-6 scrollbar-hide">
          <div v-for="n in 4" :key="n" class="min-w-[120px] text-center group cursor-pointer">
            <div class="w-24 h-24 bg-white rounded-full shadow-lg border-4 border-transparent group-hover:border-[#C2F2D9] flex items-center justify-center p-4 transition-all mx-auto overflow-hidden">
              <img src="https://upload.wikimedia.org/wikipedia/commons/b/ba/Logo_Mix_Mateus.png" class="w-full h-auto grayscale group-hover:grayscale-0 transition-all" />
            </div>
            <p class="mt-3 font-black text-[10px] uppercase tracking-widest opacity-60 italic">Mix Mateus</p>
          </div>
        </div>
      </section>

      <section>
        <div class="flex justify-between items-end mb-8">
          <h3 class="text-2xl font-black uppercase italic tracking-tighter">Oportunidades do Dia</h3>
          <span class="text-[#2D4483] font-black text-xs uppercase cursor-pointer hover:underline">Ver tudo</span>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
          <div v-for="n in 5" :key="n" class="relative group">
            <div @click="abrirProduto(n)" class="bg-white p-5 rounded-[2.5rem] shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all cursor-pointer border border-black/5 h-full flex flex-col">
              <div class="aspect-square bg-white rounded-[2rem] mb-4 overflow-hidden flex items-center justify-center p-4 border border-gray-50">
                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS-v1hS01fL-7kMv6pX0r8E9_9y1o-k-Y7-rA&s" class="max-h-full max-w-full object-contain" />
              </div>
              <p class="text-[10px] font-black text-[#2D4483] uppercase mb-1">Camil / 1kg</p>
              <p class="font-black text-xs uppercase leading-tight flex-1">Arroz Branco Agulhinha Tipo 1</p>
              <p class="text-[#1A1A1A] font-black mt-4 text-xl">R$ 5,89</p>
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
      <div @click="produtoSelecionado = null" class="absolute inset-0 bg-[#1A1A1A]/90 backdrop-blur-md"></div>
      <div class="relative bg-white w-full max-w-xl rounded-[3.5rem] overflow-hidden shadow-2xl">
        <button @click="produtoSelecionado = null" class="absolute top-8 right-8 w-10 h-10 bg-black/5 rounded-full font-bold z-10">✕</button>
        
        <div class="grid grid-cols-1 md:grid-cols-2">
          <div class="h-64 md:h-full bg-white p-10 flex items-center justify-center">
             <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS-v1hS01fL-7kMv6pX0r8E9_9y1o-k-Y7-rA&s" class="max-h-full max-w-full object-contain" />
          </div>
          <div class="p-10 bg-gray-50/50">
            <p class="text-[#2D4483] font-black text-xs uppercase mb-2">Alimentos</p>
            <h2 class="text-2xl font-black uppercase italic leading-tight">Arroz Branco Camil Agulhinha 1kg</h2>
            <p class="text-[#1A1A1A]/60 mt-4 text-xs font-medium leading-relaxed">Arroz de primeira qualidade, grãos selecionados e soltinhos. Ideal para o dia a dia da sua família.</p>
            
            <div class="mt-8">
              <span class="text-3xl font-black text-[#1A1A1A]">R$ 5,89</span>
            </div>

            <button @click="produtoSelecionado = null" class="w-full mt-8 py-5 bg-[#1A1A1A] text-white font-black rounded-2xl uppercase tracking-widest shadow-xl hover:bg-black transition-all">
              Adicionar Carrinho
            </button>
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
const enderecoAtual = ref('Endereço Principal...')
const produtoSelecionado = ref(null)

// Categorias de Varejo
const categorias = [
  '🍏 Hortifruti', 
  '🥩 Açougue', 
  '🥛 Laticínios', 
  '🧼 Limpeza', 
  '📦 Mercearia', 
  '🧁 Padaria', 
  '🧴 Higiene'
]

const abrirProduto = (id) => { produtoSelecionado.value = id }
const adicionarRapido = (id) => { alert(`Item adicionado!`) }
const trocarEndereco = () => { 
  const novo = prompt("Qual o novo endereço de entrega?")
  if (novo) enderecoAtual.value = novo
}
const irParaCarrinho = () => { router.push('/carrinho') }
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>