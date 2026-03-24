<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] pb-24 text-[#1A1A1A]">
    
    <nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-8">
      <div class="flex items-center gap-2">
        <span class="text-3xl">⚡</span>
        <h1 class="text-2xl font-black italic uppercase tracking-tighter">RotaCerta</h1>
      </div>
      <div class="flex gap-5 items-center">
        <router-link title="Meus Pedidos" to="/meus-pedidos" class="text-2xl hover:scale-110 transition-all">📄</router-link>
        <router-link title="Meu Perfil" to="/perfil" class="text-2xl hover:scale-110 transition-all">👤</router-link>
        <button @click="irParaCarrinho" class="relative text-2xl hover:scale-110 transition-all">
          🛒
          <span class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold italic shadow-md">3</span>
        </button>
      </div>
    </nav>

    <main class="p-6 lg:p-12 max-w-7xl mx-auto space-y-12">
      
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
        <div class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide">
          <button v-for="cat in categorias" :key="cat" class="px-8 py-4 bg-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-md hover:bg-[#1A1A1A] hover:text-white transition-all whitespace-nowrap border border-black/5">
            {{ cat }}
          </button>
        </div>
      </section>

      <section>
        <h3 class="text-xl font-black mb-6 uppercase italic tracking-tighter">Mercados e Atacados</h3>
        <div class="flex gap-8 overflow-x-auto pb-6 scrollbar-hide">
          <div v-for="n in 4" :key="n" class="min-w-[110px] text-center group cursor-pointer">
            <div class="w-24 h-24 bg-white rounded-full shadow-lg border-4 border-transparent group-hover:border-[#C2F2D9] flex items-center justify-center p-4 transition-all mx-auto overflow-hidden">
              <img src="https://mixmateus.com.br/wp-content/uploads/2022/07/Logo-Mix-Mateus.png" class="w-full h-auto grayscale group-hover:grayscale-0 transition-all" />
            </div>
            <p class="mt-3 font-black text-[9px] uppercase tracking-widest opacity-60 italic">Mix Mateus</p>
          </div>
        </div>
      </section>

      <section v-for="secao in catalogo" :key="secao.titulo">
        <h3 class="text-2xl font-black uppercase italic tracking-tighter mb-8">{{ secao.titulo }}</h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
          <div v-for="prod in secao.itens" :key="prod.id" class="relative group">
            <div @click="abrirProduto(prod)" class="bg-white p-5 rounded-[2.5rem] shadow-lg hover:shadow-2xl hover:-translate-y-2 transition-all cursor-pointer border border-black/5 h-full flex flex-col relative overflow-hidden">
              <div class="aspect-square bg-white rounded-[2rem] mb-4 overflow-hidden flex items-center justify-center p-4">
                <img :src="prod.foto" class="max-h-full max-w-full object-contain transition-transform group-hover:scale-110 duration-500" />
              </div>
              <div class="flex-1">
                <p class="text-[9px] font-black text-[#2D4483] uppercase mb-1">{{ prod.marca }}</p>
                <p class="font-bold text-xs uppercase leading-tight">{{ prod.nome }}</p>
              </div>
              <div class="mt-4 flex flex-col">
                <p v-if="prod.precoAntigo" class="text-[10px] text-red-500 font-bold line-through opacity-60">R$ {{ prod.precoAntigo.toFixed(2) }}</p>
                <p class="text-[#1A1A1A] font-black text-xl">R$ {{ prod.preco.toFixed(2) }}</p>
              </div>
              <div class="absolute bottom-5 right-5 w-8 h-8 bg-white rounded-full shadow-md z-10 flex items-center justify-center p-1 border border-black/5 overflow-hidden">
                <img src="https://mixmateus.com.br/wp-content/uploads/2022/07/Logo-Mix-Mateus.png" class="w-full h-auto object-contain" />
              </div>
            </div>
            <button @click.stop="adicionarRapido(prod)" class="absolute top-4 right-4 w-10 h-10 bg-[#1A1A1A] text-white rounded-full shadow-2xl hover:scale-110 active:scale-90 transition-all z-10 flex items-center justify-center">
              <span class="text-xl font-light leading-none mb-1">+</span>
            </button>
          </div>
        </div>
      </section>
    </main>

    <div v-if="prodSel" class="fixed inset-0 z-[100] flex items-center justify-center p-4">
      <div @click="prodSel = null" class="absolute inset-0 bg-[#1A1A1A]/90 backdrop-blur-md"></div>
      <div class="relative bg-white w-full max-w-4xl rounded-[3.5rem] overflow-hidden shadow-2xl flex flex-col md:flex-row animate-in zoom-in duration-300">
        <button @click="prodSel = null" class="absolute top-6 right-6 w-12 h-12 bg-black/5 rounded-full font-bold z-50 hover:bg-black/10 transition-all">✕</button>
        <div class="md:w-1/2 p-12 bg-white flex items-center justify-center relative border-r border-gray-100">
          <img :src="prodSel.foto" class="max-h-80 object-contain" />
        </div>
        <div class="md:w-1/2 p-10 flex flex-col justify-between">
          <div>
            <div class="flex flex-col gap-1 mb-6 pr-14">
               <div class="flex gap-2">
                 <span class="px-3 py-1 bg-[#C2F2D9] text-[#1A1A1A] text-[8px] font-black uppercase rounded-full italic">Em Estoque</span>
                 <span class="text-[9px] font-black text-gray-300 uppercase tracking-widest">REF: #{{ prodSel.id }}00</span>
               </div>
               <p class="text-[#2D4483] font-black text-xs uppercase mt-4">{{ prodSel.marca }}</p>
               <h2 class="text-3xl font-black uppercase italic leading-tight text-[#1A1A1A]">{{ prodSel.nome }}</h2>
            </div>
            <p class="text-gray-500 text-xs font-medium leading-relaxed">Produto selecionado com rigoroso controle de qualidade. Garantia de frescor e procedência {{ prodSel.lojaNome }}.</p>
          </div>
          <div class="mt-10">
            <div class="flex items-baseline gap-3 mb-6">
               <p v-if="prodSel.precoAntigo" class="text-lg text-red-500 font-bold line-through opacity-50 italic">R$ {{ prodSel.precoAntigo.toFixed(2) }}</p>
               <p class="text-5xl font-black italic tracking-tighter text-[#1A1A1A]">R$ {{ prodSel.preco.toFixed(2) }}</p>
            </div>
            <div class="flex gap-4">
              <div class="flex items-center gap-4 bg-gray-100 px-6 rounded-2xl border border-black/5 font-black">
                <button @click="qtdModal > 1 ? qtdModal-- : null" class="text-xl">-</button>
                <span class="text-lg">{{ qtdModal }}</span>
                <button @click="qtdModal++" class="text-xl">+</button>
              </div>
              <button @click="adicionarPeloModal" class="flex-1 py-5 bg-[#1A1A1A] text-white font-black rounded-2xl uppercase tracking-widest shadow-xl hover:bg-black transition-all">Adicionar Carrinho</button>
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
import arrozBranco from '../assets/arrozBranco.jpg'

const router = useRouter()
const enderecoAtual = ref('Rua das Flores, 123')
const prodSel = ref(null)
const qtdModal = ref(1)

const categorias = ['🍏 Hortifruti', '🥩 Açougue', '🧼 Limpeza', '📦 Mercearia', '🥤 Bebidas', '🍞 Padaria']

const catalogo = ref([
  {
    titulo: '🔥 Super Ofertas',
    itens: [
      { id: 1, nome: 'Arroz Branco 1kg', marca: 'Camil', preco: 5.89, precoAntigo: 7.50, foto: arrozBranco, lojaNome: 'Mix Mateus' },
      { id: 2, nome: 'Açúcar União 1kg', marca: 'União', preco: 4.20, precoAntigo: 5.10, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT6Z6vY5_N0v_Z2Mv_y_Z_Z_Z_Z_Z_Z_Z_Z_Z_A&s', lojaNome: 'Mix Mateus' },
      { id: 3, nome: 'Feijão Carioca 1kg', marca: 'Kicaldo', preco: 8.90, precoAntigo: 10.50, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRz-8jR2yUf7hE1_9_9_9_9_9_9_9_9_9_9_9_A&s', lojaNome: 'Mix Mateus' },
      { id: 4, nome: 'Óleo de Soja 900ml', marca: 'Soya', preco: 6.45, precoAntigo: 7.20, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_x_x_x_x_x_x_x_x_x_x_x_x_x_x_x_x_x_x_x_A&s', lojaNome: 'Mix Mateus' },
      { id: 5, nome: 'Café Santa Clara 250g', marca: 'Santa Clara', preco: 11.90, precoAntigo: 14.00, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_A&s', lojaNome: 'Mix Mateus' },
    ]
  },
  {
    titulo: '🧼 Higiene e Limpeza',
    itens: [
      { id: 6, nome: 'Detergente Ypê 500ml', marca: 'Ypê', preco: 2.39, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_y_y_y_y_y_y_y_y_y_y_y_y_y_y_y_y_y_y_y_A&s', lojaNome: 'Mix Mateus' },
      { id: 7, nome: 'Sabão Omo 800g', marca: 'Omo', preco: 16.50, precoAntigo: 18.90, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_u_u_u_u_u_u_u_u_u_u_u_u_u_u_u_u_u_u_u_A&s', lojaNome: 'Mix Mateus' },
      { id: 8, nome: 'Amaciante Downy', marca: 'Downy', preco: 19.90, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_z_A&s', lojaNome: 'Mix Mateus' },
      { id: 9, nome: 'Papel Neve 12un', marca: 'Neve', preco: 22.00, precoAntigo: 25.00, foto: 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_v_v_v_v_v_v_v_v_v_v_v_v_v_v_v_v_v_v_v_A&s', lojaNome: 'Mix Mateus' },
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
.scrollbar-hide::-webkit-scrollbar { display: none; }
.scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>