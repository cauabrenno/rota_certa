<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] text-[#1A1A1A] font-sans pb-12">
    
    <!-- NAVBAR DO LOJISTA (Mantendo o padrão) -->
<nav class="w-full bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-8 mb-8">
      
      <div class="flex items-center gap-3">
        <img :src="iRota" alt="Rota Certa" class="w-10 h-10 object-contain drop-shadow-sm">
        <div>
          <h1 class="text-2xl font-black italic uppercase tracking-tighter leading-none">Painel do Lojista</h1>
          <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">
            {{ $route.path === '/dashboard-lojista' ? 'Gestão de Pedidos' : ($route.path === '/lojista-produtos' ? 'Gestão de Produtos' : 'Configurações da Loja') }}
          </p>
        </div>
      </div>
      
      <div class="flex gap-6 items-center">
        <router-link 
          to="/dashboard-lojista" 
          class="text-sm font-black uppercase tracking-widest transition-colors"
          :class="$route.path === '/dashboard-lojista' ? 'text-[#2D4483]' : 'text-gray-400 hover:text-[#1A1A1A]'"
        >
          Dashboard
        </router-link>

        <router-link 
          to="/lojista-produtos" 
          class="text-sm font-black uppercase tracking-widest transition-colors"
          :class="$route.path === '/lojista-produtos' ? 'text-[#2D4483]' : 'text-gray-400 hover:text-[#1A1A1A]'"
        >
          Meus Produtos
        </router-link>

        <router-link 
          to="/lojista-perfil" 
          class="text-sm font-black uppercase tracking-widest transition-colors"
          :class="$route.path === '/lojista-perfil' ? 'text-[#2D4483]' : 'text-gray-400 hover:text-[#1A1A1A]'"
        >
          Meu Perfil
        </router-link>

        <button @click="fazerLogout" class="bg-red-50 text-red-600 px-4 py-2 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-100 transition-all">
          Sair
        </button>
      </div>

    </nav>

    <main class="max-w-[1400px] mx-auto px-8">
      
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- COLUNA ESQUERDA: FORMULÁRIO DE CADASTRO -->
        <div class="lg:col-span-1">
          <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-black/5 sticky top-32">
            <h2 class="text-xl font-black italic uppercase tracking-tighter mb-6 text-[#2D4483]">
              {{ editando ? 'Editar Produto' : 'Novo Produto' }}
            </h2>

            <form @submit.prevent="salvarProduto" class="space-y-4">
              
              <!-- Imagem do Produto -->
              <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-2 ml-2">Foto do Produto</label>
                <div class="flex items-center gap-4">
                  <div v-if="produto.imagem_url" class="w-20 h-20 rounded-2xl bg-gray-100 border border-black/5 overflow-hidden flex-shrink-0 shadow-inner">
                    <img :src="produto.imagem_url" class="w-full h-full object-cover" />
                  </div>
                  <div v-else class="w-20 h-20 rounded-2xl bg-gray-50 border-2 border-dashed border-gray-200 flex items-center justify-center flex-shrink-0 text-2xl text-gray-300">
                    📷
                  </div>
                  
                  <label class="flex-1 bg-gray-50 hover:bg-gray-100 border border-black/5 rounded-2xl p-4 text-center cursor-pointer transition-colors">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-500">
                      {{ produto.imagem_url ? 'Trocar Imagem' : 'Fazer Upload' }}
                    </span>
                    <input type="file" accept="image/*" @change="handleImagem" class="hidden" />
                  </label>
                </div>
              </div>

              <!-- Nome -->
              <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Nome</label>
                <input v-model="produto.nome" type="text" placeholder="Ex: Queijo Coalho Tradicional" required class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
              </div>

              <!-- Marca e Categoria -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Marca</label>
                  <input v-model="produto.marca" type="text" placeholder="Ex: Deleite do Araripe" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
                </div>
                <div>
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Categoria</label>
                  <input v-model="produto.categoria" type="text" placeholder="Ex: Laticínios" required class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
                </div>
              </div>

              <!-- Preços -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-[9px] font-black uppercase tracking-widest text-[#2D4483] mb-1 ml-2">Preço de Venda (R$)</label>
                  <input v-model="produto.preco" type="number" min="0" step="0.01" placeholder="0.00" required class="w-full p-4 bg-blue-50/50 rounded-2xl border border-blue-100 outline-none font-black text-sm text-[#2D4483]" />
                </div>
                <div>
                  <label class="block text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1 ml-2">Preço Antigo (Opcional)</label>
                  <input v-model="produto.preco_antigo" type="number" min="0" step="0.01" placeholder="0.00" class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm text-gray-500 line-through decoration-red-400" />
                </div>
              </div>

              <!-- Botões -->
              <div class="pt-4 flex gap-3">
                <button v-if="editando" type="button" @click="cancelarEdicao" class="py-4 px-6 bg-gray-100 text-[#1A1A1A] rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-gray-200 transition-all">
                  Cancelar
                </button>
                <button type="submit" class="flex-1 bg-[#1A1A1A] text-[#C2F2D9] py-4 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black shadow-xl transition-all">
                  {{ editando ? 'Atualizar Produto' : 'Cadastrar Produto' }}
                </button>
              </div>

            </form>
          </div>
        </div>

        <!-- COLUNA DIREITA: VITRINE DE PRODUTOS -->
        <div class="lg:col-span-2">
          <div class="bg-white p-8 rounded-[2.5rem] shadow-xl border border-black/5 min-h-[500px]">
            
            <div class="flex justify-between items-center mb-8">
              <h2 class="text-xl font-black italic uppercase tracking-tighter">Meu Catálogo</h2>
              <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-lg text-[10px] font-black tracking-widest uppercase">
                {{ produtos.length }} itens
              </span>
            </div>

            <div v-if="carregando" class="text-center py-10 font-black uppercase tracking-widest text-gray-400 text-xs">
              Carregando catálogo...
            </div>

            <div v-else-if="produtos.length === 0" class="text-center py-20 flex flex-col items-center">
              <span class="text-5xl mb-4 grayscale opacity-20">🧀</span>
              <p class="font-black text-gray-400 uppercase tracking-widest text-xs">Sua vitrine está vazia.</p>
              <p class="text-[10px] text-gray-400 mt-2">Cadastre seu primeiro produto ao lado.</p>
            </div>

            <!-- GRID DE PRODUTOS -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="p in produtos" :key="p.id" class="bg-gray-50 p-4 rounded-3xl border border-black/5 flex gap-4 hover:shadow-md transition-shadow group relative overflow-hidden">
                
                <!-- Imagem Redonda -->
                <div class="w-20 h-20 bg-white rounded-2xl border border-black/5 shadow-sm flex-shrink-0 flex items-center justify-center overflow-hidden">
                  <img v-if="p.imagem_url" :src="p.imagem_url" class="w-full h-full object-cover" />
                  <span v-else class="text-2xl opacity-20">📦</span>
                </div>

                <!-- Infos -->
                <div class="flex-1 flex flex-col justify-center">
                  <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-0.5">{{ p.categoria }}</p>
                  <p class="font-black text-sm uppercase leading-tight text-[#1A1A1A] line-clamp-1" :title="p.nome">{{ p.nome }}</p>
                  <p class="text-[10px] text-gray-500 font-medium truncate w-32" :title="p.marca">{{ p.marca || 'Sem marca' }}</p>
                  
                  <div class="flex items-baseline gap-2 mt-2">
                    <span class="font-black text-[#2D4483]">R$ {{ Number(p.preco).toFixed(2) }}</span>
                    <span v-if="p.preco_antigo" class="text-[9px] text-gray-400 line-through font-bold">R$ {{ Number(p.preco_antigo).toFixed(2) }}</span>
                  </div>
                </div>

                <!-- Menu de Ações (Aparece no Hover) -->
                <div class="absolute right-0 top-0 bottom-0 w-16 flex flex-col opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-l from-white via-white to-transparent pr-4 justify-center items-end gap-2">
                  <button @click="editarProduto(p)" class="w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Editar">
                    ✎
                  </button>
                  <button @click="removerProduto(p.id)" class="w-8 h-8 bg-red-50 text-red-600 rounded-full flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors" title="Excluir">
                    ✕
                  </button>
                </div>

              </div>
            </div>

          </div>
        </div>

      </div>
    </main>
  </div>
</template>

<script setup>
import iRota from '../assets/iRota.png'
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '../services/api' // Certifique-se que o Axios está configurado aqui

const router = useRouter()
const carregando = ref(true)

// Mapeado EXATAMENTE com as colunas do seu banco de dados
const produto = ref({
  nome: '',
  marca: '',
  categoria: '',
  preco: '',
  preco_antigo: '',
  imagem_url: null
})

const produtos = ref([])
const editando = ref(false)
let idEditando = null

// Converte a imagem para Base64 para enviar ao banco
const handleImagem = (event) => {
  const file = event.target.files[0]
  if (!file) return

  const reader = new FileReader()
  reader.onload = (e) => {
    produto.value.imagem_url = e.target.result // Atualiza a variável que vai pro DB
  }
  reader.readAsDataURL(file)
}

// === INTEGRAÇÃO COM A API (Laravel) ===

const buscarProdutos = async () => {
  try {
    const res = await api.get('/produtos') // Ajustar para a rota do seu lojista
    produtos.value = res.data
  } catch (error) {
    console.error("Erro ao buscar produtos:", error)
  } finally {
    carregando.value = false
  }
}

onMounted(() => {
  buscarProdutos()
})

const salvarProduto = async () => {
  try {
    if (editando.value) {
      // Atualizar Produto Existente (PUT)
      await api.put(`/produtos/${idEditando}`, produto.value)
      alert("Produto atualizado com sucesso!")
    } else {
      // Criar Novo Produto (POST)
      await api.post('/produtos', produto.value)
      alert("Produto cadastrado com sucesso!")
    }
    
    cancelarEdicao()
    buscarProdutos() // Recarrega a lista
  } catch (error) {
    alert("Erro ao salvar produto. Verifique a conexão com o servidor.")
    console.error(error)
  }
}

const editarProduto = (p) => {
  produto.value = { ...p }
  editando.value = true
  idEditando = p.id
  window.scrollTo({ top: 0, behavior: 'smooth' }) // Sobe a tela para o form
}

const cancelarEdicao = () => {
  editando.value = false
  idEditando = null
  produto.value = { nome: '', marca: '', categoria: '', preco: '', preco_antigo: '', imagem_url: null }
}

const removerProduto = async (id) => {
  if (!confirm('Deseja realmente remover este produto do seu catálogo?')) return

  try {
    await api.delete(`/produtos/${id}`)
    buscarProdutos() // Atualiza a vitrine
  } catch (error) {
    alert("Erro ao excluir produto.")
  }
}

const fazerLogout = () => {
  if(confirm("Deseja sair do painel do lojista?")) {
    localStorage.removeItem('token')
    router.push('/')
  }
}
</script>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>