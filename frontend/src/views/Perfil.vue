<template>
  <div class="min-h-screen bg-gradient-to-b from-[#F8FAFC] to-[#E2F7ED] pb-32 md:pb-24 text-[#1A1A1A] relative overflow-x-hidden">
    
<nav class="bg-white/80 backdrop-blur-md p-6 sticky top-0 z-40 shadow-sm border-b border-black/5 flex justify-between items-center px-6 md:px-8">
  <div class="flex items-center gap-3">
    <img :src="iRota" alt="Rota Certa" class="w-10 h-10 object-contain drop-shadow-sm">
    <h1 class="text-2xl font-black italic uppercase tracking-tighter">Perfil</h1>
  </div>
  
  <div class="hidden md:flex gap-6 items-center">
    <router-link title="Início" to="/home" class="text-2xl hover:scale-110 transition-all opacity-100">🏠</router-link>
    <router-link title="Meus Pedidos" to="/meus-pedidos" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">📄</router-link>
    <router-link title="Meu Perfil" to="/perfil" class="text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">👤</router-link>
    <button @click="irParaCarrinho" class="relative text-2xl hover:scale-110 transition-all opacity-60 hover:opacity-100">
      🛒
      <span class="absolute -top-2 -right-2 bg-[#2D4483] text-white text-[10px] w-5 h-5 rounded-full flex items-center justify-center font-bold italic shadow-md">3</span>
    </button>
  </div>
</nav>

    <div class="md:hidden fixed bottom-6 left-1/2 -translate-x-1/2 w-[92%] max-w-[400px] bg-[#1A1A1A] rounded-[2rem] shadow-[0_10px_50px_rgba(0,0,0,0.4)] border border-white/10 flex justify-between items-center px-6 py-4 z-50">
      <router-link to="/home" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">🏠</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Início</span>
      </router-link>

      <router-link to="/meus-pedidos" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all">
        <div class="p-2"><span class="text-2xl grayscale opacity-80">📄</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Pedidos</span>
      </router-link>

      <button @click="irParaCarrinho" class="flex flex-col items-center gap-1 text-white/40 hover:text-white transition-all relative">
        <div class="p-2 relative">
          <span class="text-2xl grayscale opacity-80">🛒</span>
          <span class="absolute top-1 right-0 bg-[#C2F2D9] text-[#1A1A1A] text-[9px] w-4 h-4 rounded-full flex items-center justify-center font-black shadow-md">3</span>
        </div>
        <span class="text-[9px] font-black uppercase tracking-widest">Cesta</span>
      </button>

      <router-link to="/perfil" class="flex flex-col items-center gap-1 text-[#C2F2D9]">
        <div class="bg-[#C2F2D9]/20 p-2 rounded-xl transition-all"><span class="text-2xl">👤</span></div>
        <span class="text-[9px] font-black uppercase tracking-widest">Perfil</span>
      </router-link>
    </div>

    <main class="p-4 md:p-6 lg:p-12 max-w-3xl mx-auto space-y-6 lg:space-y-8 mt-4">
      
      <section class="bg-white rounded-[2.5rem] p-8 shadow-xl border border-black/5 flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
        <div class="w-24 h-24 bg-[#1A1A1A] text-white rounded-full flex items-center justify-center text-3xl font-black shadow-lg">
          {{ usuario.nome.split(' ').map(n => n[0]).join('').substring(0,2) }}
        </div>
        <div class="flex-1">
          <h2 class="text-3xl font-black italic tracking-tighter uppercase mb-1">{{ usuario.nome }}</h2>
          <p class="text-gray-500 font-medium text-sm">{{ usuario.email }} • {{ usuario.telefone }}</p>
          <p class="text-xs font-bold text-[#2D4483] mt-2 tracking-widest uppercase">Cliente desde 2026</p>
        </div>
        <button @click="abrirModal('editarPerfil')" class="bg-gray-100 text-[#1A1A1A] px-6 py-3 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-[#C2F2D9] transition-all">
          Editar Perfil
        </button>
      </section>

      <section>
        <div class="bg-gradient-to-r from-[#1A1A1A] to-[#2D4483] rounded-[2rem] p-6 text-white shadow-xl flex justify-between items-center relative overflow-hidden">
          <div class="absolute -right-6 -top-10 w-32 h-32 bg-white/5 rounded-full blur-xl"></div>
          <div class="relative z-10">
            <p class="text-[9px] uppercase tracking-widest text-[#C2F2D9] font-black mb-1">Clube RotaCerta</p>
            <div class="flex items-baseline gap-2">
              <span class="text-3xl font-black italic tracking-tighter">{{ usuario.pontos }}</span>
              <span class="text-xs font-bold opacity-80">pontos</span>
            </div>
          </div>
          <button @click="abrirModal('clube')" class="relative z-10 bg-[#C2F2D9] text-[#1A1A1A] px-5 py-3 rounded-xl font-black text-[9px] uppercase tracking-widest hover:scale-105 active:scale-95 transition-all shadow-lg">
            Ver Benefícios
          </button>
        </div>
      </section>

      <section class="bg-white rounded-[2.5rem] p-4 lg:p-6 shadow-xl border border-black/5">
        <div class="flex flex-col">
          <button @click="abrirModal('enderecos')" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition-all group border-b border-gray-50">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-lg group-hover:bg-[#C2F2D9] group-hover:text-[#1A1A1A] transition-colors">📍</div>
              <div class="text-left">
                <p class="font-bold text-sm uppercase tracking-tight">Meus Endereços</p>
                <p class="text-[10px] text-gray-400 font-medium">Gerencie os locais de entrega</p>
              </div>
            </div>
            <span class="text-gray-300 font-bold group-hover:text-[#1A1A1A] group-hover:translate-x-1 transition-all">➔</span>
          </button>

          <button @click="abrirModal('pagamentos')" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition-all group border-b border-gray-50">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-lg group-hover:bg-[#C2F2D9] group-hover:text-[#1A1A1A] transition-colors">💳</div>
              <div class="text-left">
                <p class="font-bold text-sm uppercase tracking-tight">Pagamentos</p>
                <p class="text-[10px] text-gray-400 font-medium">Cartões e chaves Pix salvas</p>
              </div>
            </div>
            <span class="text-gray-300 font-bold group-hover:text-[#1A1A1A] group-hover:translate-x-1 transition-all">➔</span>
          </button>

          <button @click="abrirModal('notificacoes')" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition-all group border-b border-gray-50">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-lg group-hover:bg-[#C2F2D9] group-hover:text-[#1A1A1A] transition-colors">🔔</div>
              <div class="text-left">
                <p class="font-bold text-sm uppercase tracking-tight">Notificações</p>
                <p class="text-[10px] text-gray-400 font-medium">Avisos de ofertas e pedidos</p>
              </div>
            </div>
            <span class="text-gray-300 font-bold group-hover:text-[#1A1A1A] group-hover:translate-x-1 transition-all">➔</span>
          </button>

          <button @click="abrirModal('seguranca')" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition-all group border-b border-gray-50">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-lg group-hover:bg-[#C2F2D9] group-hover:text-[#1A1A1A] transition-colors">🛡️</div>
              <div class="text-left">
                <p class="font-bold text-sm uppercase tracking-tight">Segurança e Senha</p>
                <p class="text-[10px] text-gray-400 font-medium">Altere sua senha de acesso</p>
              </div>
            </div>
            <span class="text-gray-300 font-bold group-hover:text-[#1A1A1A] group-hover:translate-x-1 transition-all">➔</span>
          </button>
          
          <button @click="abrirModal('suporte')" class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-2xl transition-all group">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center text-lg group-hover:bg-[#C2F2D9] group-hover:text-[#1A1A1A] transition-colors">🎧</div>
              <div class="text-left">
                <p class="font-bold text-sm uppercase tracking-tight">Suporte</p>
                <p class="text-[10px] text-gray-400 font-medium">Fale com o RotaCerta</p>
              </div>
            </div>
            <span class="text-gray-300 font-bold group-hover:text-[#1A1A1A] group-hover:translate-x-1 transition-all">➔</span>
          </button>
        </div>
      </section>

      <section class="pt-4">
        <button @click="fazerLogout" class="w-full bg-red-50 text-red-600 border border-red-100 py-5 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-red-600 hover:text-white transition-all shadow-sm">
          Sair da Conta
        </button>
      </section>

    </main>

    <div v-if="modalAtivo === 'editarPerfil'" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <div class="mb-8 text-center">
          <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-1">Editar Perfil</h2>
          <p class="text-gray-500 text-xs font-medium">Mantenha seus dados atualizados</p>
        </div>

        <form @submit.prevent="salvarPerfil" class="space-y-4">
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Nome Completo</label>
            <input v-model="formPerfil.nome" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">E-mail</label>
            <input v-model="formPerfil.email" type="email" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Telefone (WhatsApp)</label>
            <input v-model="formPerfil.telefone" type="tel" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <button type="submit" class="w-full bg-[#1A1A1A] text-[#C2F2D9] py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black shadow-xl mt-4 transition-all">
            Salvar Alterações
          </button>
        </form>
      </div>
    </div>

    <div v-if="modalAtivo === 'clube'" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <div class="text-center mb-8">
          <span class="text-5xl mb-4 block">🏆</span>
          <h2 class="text-2xl font-black italic tracking-tighter uppercase">Clube RotaCerta</h2>
          <p class="text-gray-500 text-xs mt-2">Você tem <span class="font-bold text-[#1A1A1A]">{{ usuario.pontos }} pontos</span></p>
        </div>

        <div class="bg-gray-50 p-5 rounded-2xl border border-black/5 mb-8">
          <div class="flex justify-between text-[10px] font-black uppercase tracking-widest mb-2">
            <span class="text-[#2D4483]">Nível Prata</span>
            <span class="text-gray-400">Ouro (400 pts)</span>
          </div>
          <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
            <div class="bg-gradient-to-r from-[#2D4483] to-[#C2F2D9] h-3 rounded-full" style="width: 85%"></div>
          </div>
          <p class="text-[9px] text-center text-gray-400 mt-3 font-medium">Faltam 60 pontos para o próximo nível!</p>
        </div>

        <div class="space-y-3">
          <h3 class="text-xs font-black uppercase tracking-widest opacity-40 mb-3">Resgatar Benefícios</h3>
          <div v-for="cupom in cupons" :key="cupom.id" class="flex justify-between items-center bg-white p-4 rounded-2xl border border-black/5 shadow-sm">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-[#1A1A1A] text-[#C2F2D9] rounded-xl flex items-center justify-center text-xl">🎟️</div>
              <div>
                <p class="font-black text-sm uppercase">{{ cupom.titulo }}</p>
                <p class="text-[10px] text-gray-400 font-bold">{{ cupom.custo }} pts</p>
              </div>
            </div>
            <button :disabled="usuario.pontos < cupom.custo" :class="usuario.pontos >= cupom.custo ? 'bg-[#1A1A1A] text-white hover:scale-105' : 'bg-gray-100 text-gray-400'" class="px-4 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest transition-all">
              Resgatar
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-if="modalAtivo === 'enderecos'" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300 max-h-[90vh] overflow-y-auto custom-scrollbar">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <div class="mb-8">
          <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-1">Endereços</h2>
          <p class="text-gray-500 text-xs font-medium">Onde vamos entregar suas compras?</p>
        </div>

        <div v-if="!mostrandoForm" class="space-y-4">
          <div v-for="(end, index) in enderecosSalvos" :key="index" class="flex items-start gap-4 p-4 rounded-2xl border border-black/5 bg-gray-50 relative group">
            <div class="text-2xl">📍</div>
            <div class="flex-1">
              <p class="font-black text-xs uppercase">{{ end.titulo }}</p>
              <p class="text-[11px] text-gray-500 mt-1">{{ end.rua }}, {{ end.numero }}</p>
              <p class="text-[11px] text-gray-500">{{ end.bairro }} - {{ end.cidade }}</p>
            </div>
            <button class="text-red-500 text-[10px] font-black uppercase opacity-0 group-hover:opacity-100 transition-all">Excluir</button>
          </div>

          <button @click="mostrandoForm = true" class="w-full py-5 border-2 border-dashed border-gray-200 rounded-2xl text-gray-400 font-black uppercase tracking-widest text-xs hover:border-[#1A1A1A] hover:text-[#1A1A1A] transition-all">
            + Adicionar Novo Endereço
          </button>
        </div>

        <form v-if="mostrandoForm" @submit.prevent="salvarEndereco" class="space-y-4">
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Nome do Local</label>
            <input v-model="novoEndereco.titulo" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
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
          <div class="grid grid-cols-2 gap-4">
            <button type="button" @click="mostrandoForm = false" class="py-4 font-black uppercase text-[10px] tracking-widest text-gray-400 hover:text-[#1A1A1A]">Cancelar</button>
            <button type="submit" class="bg-[#1A1A1A] text-[#C2F2D9] py-4 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-black">Salvar</button>
          </div>
        </form>
      </div>
    </div>

    <div v-if="modalAtivo === 'pagamentos'" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300 max-h-[90vh] overflow-y-auto custom-scrollbar">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <div class="mb-8">
          <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-1">Pagamentos</h2>
          <p class="text-gray-500 text-xs font-medium">Suas carteiras e cartões</p>
        </div>

        <div v-if="!mostrandoForm" class="space-y-4">
          <div v-for="(cartao, index) in cartoesSalvos" :key="index" class="flex items-center gap-4 p-4 rounded-2xl border border-black/5 bg-[#1A1A1A] text-white relative group">
            <div class="text-2xl">💳</div>
            <div class="flex-1">
              <p class="font-black text-xs uppercase">{{ cartao.bandeira }}</p>
              <p class="text-[11px] text-gray-400 mt-1">Final {{ cartao.final }}</p>
            </div>
            <button class="text-red-400 text-[10px] font-black uppercase opacity-0 group-hover:opacity-100 transition-all">Excluir</button>
          </div>

          <button @click="mostrandoForm = true" class="w-full py-5 border-2 border-dashed border-gray-200 rounded-2xl text-gray-400 font-black uppercase tracking-widest text-xs hover:border-[#1A1A1A] hover:text-[#1A1A1A] transition-all">
            + Adicionar Novo Cartão
          </button>
        </div>

        <form v-if="mostrandoForm" @submit.prevent="salvarCartao" class="space-y-4">
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Número do Cartão</label>
            <input v-model="novoCartao.numero" type="text" placeholder="0000 0000 0000 0000" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Nome Impresso</label>
            <input v-model="novoCartao.nome" type="text" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Validade</label>
              <input v-model="novoCartao.validade" type="text" placeholder="MM/AA" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
            </div>
            <div>
              <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">CVV</label>
              <input v-model="novoCartao.cvv" type="text" placeholder="123" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
            </div>
          </div>
          <div class="grid grid-cols-2 gap-4 mt-2">
            <button type="button" @click="mostrandoForm = false" class="py-4 font-black uppercase text-[10px] tracking-widest text-gray-400 hover:text-[#1A1A1A]">Cancelar</button>
            <button type="submit" class="bg-[#1A1A1A] text-[#C2F2D9] py-4 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-black">Salvar Cartão</button>
          </div>
        </form>
      </div>
    </div>

    <div v-if="modalAtivo === 'notificacoes'" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <div class="mb-8">
          <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-1">Notificações</h2>
          <p class="text-gray-500 text-xs font-medium">Personalize seus alertas</p>
        </div>

        <div class="space-y-4">
          <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-black/5">
            <div>
              <p class="font-bold text-sm uppercase">Status de Pedidos</p>
              <p class="text-[10px] text-gray-400">Avisos sobre entrega e separação</p>
            </div>
            <div @click="notificacoesConfig.pedidos = !notificacoesConfig.pedidos" class="w-12 h-6 rounded-full cursor-pointer transition-colors relative" :class="notificacoesConfig.pedidos ? 'bg-[#1A1A1A]' : 'bg-gray-300'">
              <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-transform" :class="notificacoesConfig.pedidos ? 'translate-x-7' : 'translate-x-1'"></div>
            </div>
          </div>
          
          <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-black/5">
            <div>
              <p class="font-bold text-sm uppercase">Ofertas e Promoções</p>
              <p class="text-[10px] text-gray-400">Descontos no Clube RotaCerta</p>
            </div>
            <div @click="notificacoesConfig.promocoes = !notificacoesConfig.promocoes" class="w-12 h-6 rounded-full cursor-pointer transition-colors relative" :class="notificacoesConfig.promocoes ? 'bg-[#1A1A1A]' : 'bg-gray-300'">
              <div class="w-4 h-4 bg-white rounded-full absolute top-1 transition-transform" :class="notificacoesConfig.promocoes ? 'translate-x-7' : 'translate-x-1'"></div>
            </div>
          </div>
        </div>
        
        <button @click="fecharModal" class="w-full bg-[#1A1A1A] text-[#C2F2D9] py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black shadow-xl mt-8 transition-all">
          Concluir
        </button>
      </div>
    </div>

    <div v-if="modalAtivo === 'seguranca'" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <div class="mb-8">
          <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-1">Alterar Senha</h2>
          <p class="text-gray-500 text-xs font-medium">Mantenha sua conta protegida</p>
        </div>

        <form @submit.prevent="salvarSenha" class="space-y-4">
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Senha Atual</label>
            <input v-model="formSenha.atual" type="password" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Nova Senha</label>
            <input v-model="formSenha.nova" type="password" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Confirmar Nova Senha</label>
            <input v-model="formSenha.confirmar" type="password" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm" />
          </div>
          
          <button type="submit" class="w-full bg-[#1A1A1A] text-[#C2F2D9] py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black shadow-xl mt-4 transition-all">
            Atualizar Senha
          </button>
        </form>
      </div>
    </div>

    <div v-if="modalAtivo === 'suporte'" class="fixed inset-0 z-[100] flex items-end md:items-center justify-center p-0 md:p-4">
      <div @click="fecharModal" class="absolute inset-0 bg-[#1A1A1A]/80 backdrop-blur-sm"></div>
      <div class="relative bg-white w-full max-w-lg rounded-t-[2.5rem] md:rounded-[2.5rem] p-8 shadow-2xl animate-in slide-in-from-bottom-full md:zoom-in duration-300">
        <button @click="fecharModal" class="absolute top-6 right-6 w-10 h-10 bg-gray-100 rounded-full font-bold z-50 hover:bg-gray-200">✕</button>
        
        <div class="mb-8">
          <h2 class="text-2xl font-black italic tracking-tighter uppercase mb-1">Central de Ajuda</h2>
          <p class="text-gray-500 text-xs font-medium">Como podemos te ajudar hoje?</p>
        </div>

        <form @submit.prevent="enviarSuporte" class="space-y-4">
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Assunto</label>
            <select v-model="formSuporte.assunto" required class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm appearance-none">
              <option value="" disabled>Selecione um assunto...</option>
              <option value="atraso">Pedido Atrasado</option>
              <option value="estorno">Problema com Pagamento</option>
              <option value="dúvida">Dúvida Geral</option>
            </select>
          </div>
          <div>
            <label class="text-[9px] font-black uppercase tracking-widest text-gray-400 ml-4">Mensagem</label>
            <textarea v-model="formSuporte.mensagem" rows="4" required placeholder="Detalhe o seu problema..." class="w-full mt-1 p-4 bg-gray-50 rounded-2xl border border-black/5 outline-none font-medium text-sm resize-none"></textarea>
          </div>
          <button type="submit" class="w-full bg-[#1A1A1A] text-[#C2F2D9] py-5 rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-black shadow-xl mt-4">
            Enviar Mensagem
          </button>
        </form>
      </div>
    </div>

  </div>
</template>

<script setup>
import iRota from '../assets/iRota.png'
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

// Controle Geral dos Modais
const modalAtivo = ref(null) 
const mostrandoForm = ref(false)

// Dados do Usuário
const usuario = ref({
  nome: 'Cauã Brenno',
  email: 'caua.brenno@email.com',
  telefone: '(87) 99999-9999',
  pontos: 340
})

// Clone para edição de perfil
const formPerfil = ref({ ...usuario.value })

// Configuração de Notificações
const notificacoesConfig = ref({
  pedidos: true,
  promocoes: false
})

// Senha
const formSenha = ref({ atual: '', nova: '', confirmar: '' })

// Dados Mockados para os Formulários
const cupons = ref([
  { id: 1, titulo: 'Frete Grátis Delivery', custo: 200 },
  { id: 2, titulo: 'R$ 15 OFF na Compra', custo: 400 },
  { id: 3, titulo: 'Desconto 50% Laticínios Deleite', custo: 600 }
])

const enderecosSalvos = ref([
  { titulo: 'Casa', rua: 'Rua das Flores', numero: '123', bairro: 'Centro', cidade: 'Trindade - PE' },
])
const novoEndereco = ref({ titulo: '', cep: '', numero: '', rua: '' })

const cartoesSalvos = ref([
  { bandeira: 'Mastercard', final: '4321' }
])
const novoCartao = ref({ numero: '', nome: '', validade: '', cvv: '' })

const formSuporte = ref({ assunto: '', mensagem: '' })

// Funções de Ação
const abrirModal = (tipo) => {
  modalAtivo.value = tipo
  mostrandoForm.value = false 
  
  if (tipo === 'editarPerfil') {
    formPerfil.value = { ...usuario.value }
  }
}

const fecharModal = () => {
  modalAtivo.value = null
  formSenha.value = { atual: '', nova: '', confirmar: '' } // Limpa campos de senha ao fechar
}

const salvarPerfil = () => {
  usuario.value = { ...formPerfil.value, pontos: usuario.value.pontos }
  alert('Perfil atualizado com sucesso!')
  fecharModal()
}

const salvarSenha = () => {
  if (formSenha.value.nova !== formSenha.value.confirmar) {
    alert('As novas senhas não coincidem!')
    return
  }
  alert('Senha atualizada com segurança!')
  fecharModal()
}

const salvarEndereco = () => {
  enderecosSalvos.value.push({
    titulo: novoEndereco.value.titulo,
    rua: novoEndereco.value.rua,
    numero: novoEndereco.value.numero,
    bairro: 'Centro',
    cidade: 'Trindade - PE'
  })
  mostrandoForm.value = false
  novoEndereco.value = { titulo: '', cep: '', numero: '', rua: '' }
}

const salvarCartao = () => {
  cartoesSalvos.value.push({
    bandeira: 'Cartão Novo',
    final: novoCartao.value.numero.slice(-4) || '0000'
  })
  mostrandoForm.value = false
  novoCartao.value = { numero: '', nome: '', validade: '', cvv: '' }
}

const enviarSuporte = () => {
  alert('Mensagem enviada com sucesso! Nossa equipe retornará em breve.')
  fecharModal()
  formSuporte.value = { assunto: '', mensagem: '' }
}

const irParaCarrinho = () => {
  router.push('/carrinho')
}

const fazerLogout = () => {
  if(confirm("Tem certeza que deseja sair?")) {
    router.push('/')
  }
}
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
</style>