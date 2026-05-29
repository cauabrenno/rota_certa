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
                    <Camera :size="24" />
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
                  <h4 class="text-[9px] font-black uppercase tracking-widest text-[#2D4483] mb-1 flex items-center justify-between ml-2">
                    <span class="flex items-center gap-1"><Package :size="12" /> Categoria</span>
                    <button 
                      type="button" 
                      @click="abrirModalNovaCategoria" 
                      class="text-[9px] text-emerald-600 hover:text-emerald-700 font-black uppercase tracking-wider flex items-center gap-0.5"
                    >
                      + Criar Nova
                    </button>
                  </h4>
                  <div class="relative">
                    <button 
                      type="button"
                      @click="toggleDropdownCategorias"
                      class="w-full p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm text-[#1A1A1A] cursor-pointer flex justify-between items-center text-left"
                    >
                      <span v-if="produto.categoria" class="font-bold text-[#1A1A1A]">{{ produto.categoria }}</span>
                      <span v-else class="text-gray-400">Selecione uma categoria</span>
                      <ChevronDown :size="16" class="text-gray-500 transition-transform" :class="{ 'rotate-180': dropdownCategoriasAberto }" />
                    </button>
                    
                    <div 
                      v-if="dropdownCategoriasAberto"
                      class="absolute z-50 left-0 right-0 mt-2 bg-white rounded-2xl border border-black/5 shadow-xl max-h-60 overflow-y-auto custom-scrollbar animate-in fade-in duration-200"
                    >
                      <button
                        v-for="categoria in categorias"
                        :key="categoria.id"
                        type="button"
                        @click="selecionarCategoriaDoProduto(categoria.nome)"
                        class="w-full p-3.5 text-left text-sm font-semibold text-[#1A1A1A] hover:bg-gray-50 transition-colors flex items-center justify-between border-b border-gray-50 last:border-b-0"
                      >
                        <span>{{ categoria.nome }}</span>
                        <Check v-if="produto.categoria === categoria.nome" :size="16" class="text-emerald-500" />
                      </button>
                    </div>
                  </div>
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
              <span class="text-5xl mb-4 grayscale opacity-20"><Box :size="48" /></span>
              <p class="font-black text-gray-400 uppercase tracking-widest text-xs">Sua vitrine está vazia.</p>
              <p class="text-[10px] text-gray-400 mt-2">Cadastre seu primeiro produto ao lado.</p>
            </div>

            <!-- GRID DE PRODUTOS AGRUPADOS -->
            <div v-else class="space-y-8">
              <div v-for="secao in produtosAgrupadosPorCategoria" :key="secao.titulo" class="space-y-4">
                <h3 class="text-sm font-black uppercase italic tracking-tighter text-gray-500 border-b border-black/5 pb-2">
                  {{ secao.titulo }}
                </h3>
                
                <!-- Fileira horizontal rolável para cada categoria -->
                <div class="flex gap-4 overflow-x-auto pb-4 custom-scrollbar snap-x snap-mandatory px-1">
                  <div 
                    v-for="p in secao.itens" 
                    :key="p.id" 
                    class="min-w-[280px] md:min-w-[320px] max-w-[340px] flex-shrink-0 snap-start bg-gray-50 p-4 rounded-3xl border border-black/5 flex gap-4 hover:shadow-md transition-shadow group relative overflow-hidden"
                  >
                    
                    <!-- Imagem Redonda -->
                    <div class="w-20 h-20 bg-white rounded-2xl border border-black/5 shadow-sm flex-shrink-0 flex items-center justify-center overflow-hidden">
                      <img v-if="p.imagem_url" :src="p.imagem_url" class="w-full h-full object-cover" />
                      <span v-else class="text-2xl opacity-20"><Box :size="24" /></span>
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
                    <div class="absolute right-0 top-0 bottom-0 w-16 flex flex-col opacity-0 group-hover:opacity-100 transition-opacity bg-gradient-to-l from-[#f9fafb] via-[#f9fafb] to-transparent pr-4 justify-center items-end gap-2">
                      <button @click="editarProduto(p)" class="w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Editar">
                        <Pencil :size="16" />
                      </button>
                      <button @click="removerProduto(p.id)" class="w-8 h-8 bg-red-50 text-red-600 rounded-full flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors" title="Excluir">
                        <Trash2 :size="16" />
                      </button>
                    </div>

                  </div>
                </div>
              </div>
            </div>         </div>
          </div>
        </div>
      </main>

    <!-- Modal de Criação de Categoria -->
    <div 
      v-if="modalNovaCategoriaAberto" 
      class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
      <div class="relative bg-white w-full max-w-md rounded-[2.5rem] p-8 shadow-2xl border border-black/5 animate-in zoom-in duration-300">
        <button 
          @click="fecharModalNovaCategoria" 
          class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200 flex items-center justify-center transition-all"
        >
          <X :size="20" />
        </button>
        
        <div class="mb-6">
          <h2 class="text-xl font-black italic tracking-tighter uppercase mb-1">Nova Categoria</h2>
          <p class="text-gray-500 text-xs font-semibold">Crie uma nova categoria de produtos para seu estabelecimento</p>
        </div>

        <form @submit.prevent="salvarNovaCategoria" class="space-y-4">
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-2">Nome da Categoria</label>
            <input 
              v-model="novaCategoriaNome" 
              type="text" 
              placeholder="Ex: Doces e Sobremesas" 
              required
              class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm text-[#1A1A1A]" 
            />
          </div>

          <div class="grid grid-cols-2 gap-4 pt-2">
            <button 
              type="button" 
              @click="fecharModalNovaCategoria" 
              class="py-4 font-black uppercase text-[10px] tracking-widest text-gray-400 hover:text-[#1A1A1A] transition-colors border border-gray-100 rounded-xl"
            >
              Cancelar
            </button>
            <button 
              type="submit" 
              :disabled="!novaCategoriaNome.trim()"
              class="bg-[#1A1A1A] text-[#C2F2D9] py-4 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
            >
              Salvar
            </button>
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
import { exibirNotificacao, solicitarConfirmacao } from '../utils/sistemaDeNotificacoes.js'
import { 
  Camera, 
  Package, 
  Box, 
  Pencil, 
  X, 
  Plus, 
  Check, 
  Trash2, 
  Milk, 
  Apple, 
  Beef, 
  Sparkles, 
  CupSoda, 
  Croissant,
  ChevronDown
} from 'lucide-vue-next'

const roteador = useRouter()
const carregando = ref(true)

// DADOS DE CATEGORIAS DINÂMICAS E DROPDOWNS
const dropdownCategoriasAberto = ref(false)
const modalNovaCategoriaAberto = ref(false)
const novaCategoriaNome = ref('')
const categoriasDinamicas = ref([])

const categorias = computed(() => {
  const categoriasPadrao = [
    { id: 'Mercearia', nome: 'Mercearia', icon: Package },
    { id: 'Laticínios', nome: 'Laticínios', icon: Milk },
    { id: 'Bebidas', nome: 'Bebidas', icon: CupSoda },
    { id: 'Padaria', nome: 'Padaria', icon: Croissant },
    { id: 'Hortifruti', nome: 'Hortifruti', icon: Apple },
    { id: 'Açougue', nome: 'Açougue', icon: Beef },
    { id: 'Limpeza', nome: 'Limpeza', icon: Sparkles }
  ]
  
  const categoriasPersonalizadas = categoriasDinamicas.value.map(categoriaObtida => ({
    id: String(categoriaObtida.id),
    nome: categoriaObtida.nome,
    icon: Package
  }))
  
  return [...categoriasPadrao, ...categoriasPersonalizadas]
})

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
    const respostaDeProdutos = await api.get('/meus-produtos')
    produtos.value = respostaDeProdutos.data
  } catch (erroOcorrido) {
    console.error("Erro ao buscar produtos:", erroOcorrido)
  } finally {
    carregando.value = false
  }
}

const buscarCategoriasDinamicas = async () => {
  try {
    const respostaDeCategorias = await api.get('/lojista/categorias')
    categoriasDinamicas.value = respostaDeCategorias.data
  } catch (erroOcorrido) {
    console.error("Erro ao buscar categorias dinâmicas:", erroOcorrido)
  }
}

// === COMPUTAÇÃO DO CATÁLOGO AGRUPADO ===
const produtosAgrupadosPorCategoria = computed(() => {
  const gruposDeCategorias = {}
  produtos.value.forEach(produto => {
    const nomeDaCategoria = produto.categoria || 'Diversos'
    if (!gruposDeCategorias[nomeDaCategoria]) {
      gruposDeCategorias[nomeDaCategoria] = []
    }
    gruposDeCategorias[nomeDaCategoria].push(produto)
  })
  
  const secoesDeCategorias = Object.keys(gruposDeCategorias).map(nomeDaCategoria => {
    return {
      titulo: nomeDaCategoria,
      itens: gruposDeCategorias[nomeDaCategoria]
    }
  })
  
  return secoesDeCategorias.sort((primeiraCategoria, segundaCategoria) => 
    primeiraCategoria.titulo.localeCompare(segundaCategoria.titulo)
  )
})

const toggleDropdownCategorias = () => {
  dropdownCategoriasAberto.value = !dropdownCategoriasAberto.value
}

const selecionarCategoriaDoProduto = (nomeDaCategoria) => {
  produto.value.categoria = nomeDaCategoria
  dropdownCategoriasAberto.value = false
}

const abrirModalNovaCategoria = () => {
  modalNovaCategoriaAberto.value = true
  novaCategoriaNome.value = ''
}

const fecharModalNovaCategoria = () => {
  modalNovaCategoriaAberto.value = false
  novaCategoriaNome.value = ''
}

const salvarNovaCategoria = async () => {
  if (!novaCategoriaNome.value.trim()) return

  try {
    await api.post('/lojista/categorias', {
      nome: novaCategoriaNome.value.trim()
    })
    
    exibirNotificacao("Categoria criada com sucesso!", "sucesso")
    fecharModalNovaCategoria()
    await buscarCategoriasDinamicas()
  } catch (erroOcorrido) {
    console.error("Erro ao criar categoria:", erroOcorrido)
    const mensagemErro = erroOcorrido.response?.data?.mensagem || "Não foi possível criar a categoria."
    exibirNotificacao(mensagemErro, "erro")
  }
}

onMounted(() => {
  buscarProdutos()
  buscarCategoriasDinamicas()
})

const salvarProduto = async () => {
  try {
    if (editando.value) {
      // Atualizar Produto Existente (PUT)
      await api.put(`/produtos/${idEditando}`, produto.value)
      exibirNotificacao("Produto atualizado com sucesso!", "sucesso")
    } else {
      // Criar Novo Produto (POST)
      await api.post('/produtos', produto.value)
      exibirNotificacao("Produto cadastrado com sucesso!", "sucesso")
    }
    
    cancelarEdicao()
    buscarProdutos() // Recarrega a lista
  } catch (erroOcorrido) {
    exibirNotificacao("Erro ao salvar produto. Verifique a conexão com o servidor.", "erro")
    console.error(erroOcorrido)
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
  const confirmouExcluir = await solicitarConfirmacao("Deseja realmente remover este produto do seu catálogo?")
  if (!confirmouExcluir) return

  try {
    await api.delete(`/produtos/${id}`)
    buscarProdutos() // Atualiza a vitrine
    exibirNotificacao("Produto removido com sucesso!", "sucesso")
  } catch (erroOcorrido) {
    exibirNotificacao("Erro ao excluir produto.", "erro")
  }
}

const fazerLogout = async () => {
  const confirmouDeslogar = await solicitarConfirmacao("Deseja sair do painel do lojista?")
  if (confirmouDeslogar) {
    localStorage.removeItem('token')
    localStorage.removeItem('tipoUsuario')
    roteador.push('/')
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