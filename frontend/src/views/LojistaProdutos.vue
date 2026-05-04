<template>
  <div class="min-h-screen bg-[#F8FAFC] p-6">
    <div class="max-w-7xl mx-auto">

      <!-- HEADER -->
      <header class="mb-10">
        <h1 class="text-2xl font-black uppercase italic">
          Cadastro de Produtos
        </h1>
        <p class="text-gray-400 text-xs uppercase">
          Gerencie os produtos da loja
        </p>
      </header>

      <!-- FORM -->
      <div class="bg-white p-6 rounded-3xl shadow mb-8">
        <h2 class="font-black mb-4 uppercase text-sm">
          {{ editando ? 'Editar Produto' : 'Novo Produto' }}
        </h2>

        <div class="grid grid-cols-2 gap-4">

          <input v-model="produto.nome" placeholder="Nome" class="input" />

          <input
            v-model="produto.preco"
            type="number"
            min="0"
            step="0.01"
            placeholder="Preço"
            class="input"
          />

          <input
            v-model="produto.categoria"
            placeholder="Categoria"
            class="input col-span-2"
          />

          <textarea
            v-model="produto.descricao"
            placeholder="Descrição"
            class="input col-span-2"
          ></textarea>

          <!-- IMAGEM ESTILIZADA -->
          <div class="col-span-2">
            <label class="block text-xs font-black uppercase text-gray-400 mb-2">
              Imagem do Produto
            </label>

            <label class="input flex items-center justify-center cursor-pointer">
              <span class="text-gray-500 text-sm">
                {{ produto.imagem ? 'Trocar imagem' : 'Selecionar imagem' }}
              </span>

              <input
                type="file"
                accept="image/*"
                @change="handleImagem"
                class="hidden"
              />
            </label>
          </div>

          <!-- PREVIEW -->
          <div v-if="produto.imagem" class="col-span-2 flex justify-start">
            <img
              :src="produto.imagem"
              class="w-32 h-32 object-cover rounded-xl border"
            />
          </div>

        </div>

        <button
          @click="salvarProduto"
          class="mt-4 bg-black text-white px-6 py-3 rounded-xl text-xs font-black uppercase"
        >
          {{ editando ? 'Atualizar' : 'Cadastrar' }}
        </button>
      </div>

      <!-- LISTA -->
      <div class="bg-white p-6 rounded-3xl shadow">
        <h2 class="font-black mb-4 uppercase text-sm">Produtos</h2>

        <div
          v-for="p in produtos"
          :key="p.id"
          class="flex justify-between items-center p-3 bg-gray-50 rounded-xl mb-2"
        >
          <div class="flex items-center gap-4">

            <!-- IMAGEM -->
            <img
              v-if="p.imagem"
              :src="p.imagem"
              class="w-12 h-12 object-cover rounded-lg"
            />

            <div>
              <p class="font-black">{{ p.nome }}</p>
              <p class="text-xs text-gray-400">
                R$ {{
                  Number(p.preco).toLocaleString('pt-BR', {
                    minimumFractionDigits: 2
                  })
                }}
              </p>
            </div>

          </div>

          <div class="flex gap-3">
            <button
              @click="editarProduto(p)"
              class="text-blue-500 text-xs font-black"
            >
              Editar
            </button>

            <button
              @click="removerProduto(p.id)"
              class="text-red-500 text-xs font-black"
            >
              Excluir
            </button>
          </div>
        </div>

      </div>

    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const produto = ref({
  nome: '',
  preco: '',
  categoria: '',
  descricao: '',
  imagem: null
})

const produtos = ref([])
const editando = ref(false)
let idEditando = null

const handleImagem = (event) => {
  const file = event.target.files[0]
  if (!file) return

  const reader = new FileReader()
  reader.onload = (e) => {
    produto.value.imagem = e.target.result
  }
  reader.readAsDataURL(file)
}

const salvarProduto = () => {
  if (!produto.value.nome || !produto.value.preco) {
    alert('Preencha nome e preço')
    return
  }

  if (isNaN(produto.value.preco) || Number(produto.value.preco) <= 0) {
    alert('Preço inválido')
    return
  }

  if (editando.value) {
    produtos.value = produtos.value.map(p =>
      p.id === idEditando
        ? { ...produto.value, id: idEditando }
        : p
    )
    editando.value = false
    idEditando = null
  } else {
    produtos.value.push({
      ...produto.value,
      id: Date.now()
    })
  }

  produto.value = {
    nome: '',
    preco: '',
    categoria: '',
    descricao: '',
    imagem: null
  }
}

const editarProduto = (p) => {
  produto.value = { ...p }
  editando.value = true
  idEditando = p.id
}

const removerProduto = (id) => {
  const confirmar = confirm('Deseja remover este produto?')
  if (!confirmar) return

  produtos.value = produtos.value.filter(p => p.id !== id)
}
</script>

<style scoped>
.input {
  @apply p-3 rounded-xl bg-gray-100 text-sm outline-none;
}
</style>