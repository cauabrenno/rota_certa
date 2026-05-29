import { ref } from 'vue'

// Estado reativo global para a lista de notificações ativas (toasts)
export const listaDeNotificacoes = ref([])

// Estado reativo global para uma solicitação de confirmação ativa (modal)
export const solicitacaoDeConfirmacao = ref(null)

/**
 * Adiciona uma nova notificação do tipo toast à lista.
 * 
 * @param {string} mensagem - O texto descritivo da notificação.
 * @param {string} tipo - O estilo visual ('sucesso', 'erro', 'aviso', 'informacao').
 * @param {number} duracaoEmMilissegundos - O tempo de exibição antes do desaparecimento.
 */
export function exibirNotificacao(mensagem, tipo = 'sucesso', duracaoEmMilissegundos = 4000, funcaoAoClicar = null) {
  const identificadorUnico = Date.now() + Math.random()
  
  listaDeNotificacoes.value.push({
    identificadorUnico,
    mensagem,
    tipo,
    funcaoAoClicar
  })

  setTimeout(() => {
    removerNotificacao(identificadorUnico)
  }, duracaoEmMilissegundos)

  return identificadorUnico
}

/**
 * Remove uma notificação específica da lista usando seu identificador único.
 * 
 * @param {number} identificadorUnico - O ID da notificação a ser removida.
 */
export function removerNotificacao(identificadorUnico) {
  listaDeNotificacoes.value = listaDeNotificacoes.value.filter(
    (notificacao) => notificacao.identificadorUnico !== identificadorUnico
  )
}

/**
 * Abre um diálogo de confirmação customizado e retorna uma promessa que se resolve 
 * com a resposta do usuário (verdadeiro para confirmado, falso para cancelado).
 * 
 * @param {string} mensagem - A pergunta ou aviso a ser exibido no modal.
 * @param {string} titulo - O título cabeçalho da janela modal.
 * @returns {Promise<boolean>}
 */
export function solicitarConfirmacao(mensagem, titulo = 'Confirmação Requerida') {
  return new Promise((resolverPromessa) => {
    solicitacaoDeConfirmacao.value = {
      mensagem,
      titulo,
      resolver: (respostaDoUsuario) => {
        solicitacaoDeConfirmacao.value = null
        resolverPromessa(respostaDoUsuario)
      }
    }
  })
}
