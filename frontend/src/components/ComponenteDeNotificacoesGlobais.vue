<template>
  <div>
    <!-- Conteiner de Toasts (Notificações Flutuantes no Canto Superior Direito) -->
    <div class="conteinerDeNotificacoesFlutuantes">
      <transition-group name="animacao-toast">
        <div
          v-for="notificacao in listaDeNotificacoes"
          :key="notificacao.identificadorUnico"
          @click="executarCliqueNaNotificacao(notificacao)"
          class="cartaoDeNotificacaoIndividual pointer-events-auto flex items-start gap-3 p-4 rounded-2xl bg-white/90 backdrop-blur-md border border-black/5 shadow-xl transition-all duration-300 transform"
          :class="[obterClasseDoTipo(notificacao.tipo), notificacao.funcaoAoClicar ? 'cursor-pointer hover:bg-gray-50' : '']"
        >
          <div class="flex-shrink-0 mt-0.5">
            <component :is="obterIconeDoTipo(notificacao.tipo)" :size="20" class="vetor-icone" />
          </div>
          <div class="flex-1">
            <p class="text-xs font-bold text-[#1A1A1A] leading-relaxed">
              {{ notificacao.mensagem }}
            </p>
          </div>
          <button
            @click.stop="removerNotificacao(notificacao.identificadorUnico)"
            class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors p-1"
          >
            <span class="font-bold text-xs">✕</span>
          </button>
        </div>
      </transition-group>
    </div>

    <!-- Modal de Confirmação Customizado -->
    <div
      v-if="solicitacaoDeConfirmacao"
      class="fixed inset-0 z-[9998] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
    >
      <div
        class="bg-white w-full max-w-md rounded-[2rem] p-8 shadow-2xl border border-black/5 animate-in zoom-in duration-300"
      >
        <div class="mb-6 text-center">
          <div class="w-16 h-16 bg-[#E2F7ED] text-[#2D4483] rounded-full flex items-center justify-center mx-auto mb-4 border border-[#C2F2D9]">
            <HelpCircle :size="32" />
          </div>
          <h3 class="text-lg font-black uppercase italic tracking-tighter text-[#1A1A1A] mb-2">
            {{ solicitacaoDeConfirmacao.titulo }}
          </h3>
          <p class="text-sm font-semibold text-gray-500 leading-relaxed">
            {{ solicitacaoDeConfirmacao.mensagem }}
          </p>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <button
            @click="solicitacaoDeConfirmacao.resolver(false)"
            class="py-4 font-black uppercase text-[10px] tracking-widest text-gray-400 hover:text-[#1A1A1A] transition-colors border border-gray-100 rounded-xl"
          >
            Cancelar
          </button>
          <button
            @click="solicitacaoDeConfirmacao.resolver(true)"
            class="bg-[#1A1A1A] text-[#C2F2D9] py-4 rounded-xl font-black uppercase text-[10px] tracking-widest hover:bg-black transition-all shadow-lg"
          >
            Confirmar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import {
  listaDeNotificacoes,
  removerNotificacao,
  solicitacaoDeConfirmacao
} from '../utils/sistemaDeNotificacoes.js'
import {
  CheckCircle,
  AlertTriangle,
  XCircle,
  Info,
  HelpCircle
} from 'lucide-vue-next'

/**
 * Retorna as classes CSS com base no tipo da notificação.
 * 
 * @param {string} tipo - Tipo da notificação ('sucesso', 'erro', 'aviso', 'informacao')
 * @returns {string}
 */
const obterClasseDoTipo = (tipo) => {
  switch (tipo) {
    case 'sucesso':
      return 'border-l-4 border-l-emerald-500 shadow-emerald-500/5'
    case 'erro':
      return 'border-l-4 border-l-red-500 shadow-red-500/5'
    case 'aviso':
      return 'border-l-4 border-l-amber-500 shadow-amber-500/5'
    case 'informacao':
    default:
      return 'border-l-4 border-l-blue-500 shadow-blue-500/5'
  }
}

/**
 * Retorna o ícone do pacote Lucide correspondente ao tipo da notificação.
 * 
 * @param {string} tipo - Tipo da notificação
 * @returns {object}
 */
const obterIconeDoTipo = (tipo) => {
  switch (tipo) {
    case 'sucesso':
      return CheckCircle
    case 'erro':
      return XCircle
    case 'aviso':
      return AlertTriangle
    case 'informacao':
    default:
      return Info
  }
}

/**
 * Executa a ação associada ao clique da notificação se houver uma cadastrada.
 * 
 * @param {object} notificacao - A notificação que foi clicada.
 */
const executarCliqueNaNotificacao = (notificacao) => {
  if (notificacao.funcaoAoClicar) {
    notificacao.funcaoAoClicar()
    removerNotificacao(notificacao.identificadorUnico)
  }
}
</script>

<style scoped>
.conteinerDeNotificacoesFlutuantes {
  position: fixed;
  top: 24px;
  right: 24px;
  z-index: 9999;
  display: flex;
  flex-direction: column;
  gap: 12px;
  width: 100%;
  max-width: 384px;
  pointer-events: none;
}

.cartaoDeNotificacaoIndividual {
  width: 100%;
  max-width: 100%;
  box-sizing: border-box;
  word-wrap: break-word;
  word-break: break-word;
  overflow-wrap: break-word;
}

.cartaoDeNotificacaoIndividual p {
  word-wrap: break-word;
  word-break: break-word;
  overflow-wrap: break-word;
  white-space: normal;
}

@media (max-width: 767px) {
  .conteinerDeNotificacoesFlutuantes {
    top: 16px;
    right: 5vw;
    left: 5vw;
    width: 90vw;
    max-width: 90vw;
  }
  .cartaoDeNotificacaoIndividual {
    max-width: 90vw;
  }
}

.vetor-icone {
  color: #1A1A1A;
}

/* Animação para entrada e saída dos toasts */
.animacao-toast-enter-from {
  opacity: 0;
  transform: translateX(50px) scale(0.9);
}
.animacao-toast-enter-to {
  opacity: 1;
  transform: translateX(0) scale(1);
}
.animacao-toast-leave-from {
  opacity: 1;
  transform: translateX(0) scale(1);
}
.animacao-toast-leave-to {
  opacity: 0;
  transform: translateX(50px) scale(0.9);
}
</style>
