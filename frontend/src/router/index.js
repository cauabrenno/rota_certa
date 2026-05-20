import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Selection from '../views/LoginSelection.vue'
import Register from '../views/Register.vue'
import HomeCliente from '../views/HomeCliente.vue'
import api from '../services/api'

const rotasDasPaginas = [
  // ROTAS PÚBLICAS (Qualquer um acessa)
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/selecao', component: Selection },
  { path: '/cadastro', component: Register },
  { path: '/esqueci-senha', component: () => import('../views/ForgotPassword.vue') },
  
  // ROTA: Redefinição de Senha (Pública)
  { 
    path: '/reset-password', 
    component: () => import('../views/ResetPassword.vue'),
    name: 'ResetPassword'
  },

  // ROTAS PROTEGIDAS (Exigem Token JWT e Tipo de Usuário Correto)
  { 
    path: '/home', 
    component: HomeCliente,
    meta: { requiresAuth: true, tipoDeUsuarioPermitido: 'cliente' } 
  },
  // ROTA DO LOJISTA
  { 
    path: '/dashboard-lojista', 
    component: () => import('../views/DashboardLogista.vue'),
    meta: { requiresAuth: true, tipoDeUsuarioPermitido: 'lojista' } 
  },
  // ROTA DE PRODUTOS DO LOJISTA
  { 
    path: '/lojista-produtos', 
    component: () => import('../views/LojistaProdutos.vue'),
    meta: { requiresAuth: true, tipoDeUsuarioPermitido: 'lojista' } 
  },
  // ROTA DO ENTREGADOR
  { 
    path: '/painel-entregador', 
    component: () => import('../views/EntregadorPainel.vue'),
    meta: { requiresAuth: true, tipoDeUsuarioPermitido: 'entregador' } 
  },
  { 
    path: '/lojista-perfil', 
    component: () => import('../views/LojistaPerfil.vue'),
    meta: { requiresAuth: true, tipoDeUsuarioPermitido: 'lojista' } 
  },
  { 
    path: '/meus-pedidos', 
    component: () => import('../views/MeusPedidos.vue'),
    meta: { requiresAuth: true, tipoDeUsuarioPermitido: 'cliente' } 
  },
  { 
    path: '/carrinho', 
    component: () => import('../views/Carrinho.vue'),
    meta: { requiresAuth: true, tipoDeUsuarioPermitido: 'cliente' } 
  },
  { 
    path: '/perfil', 
    component: () => import('../views/Perfil.vue'),
    meta: { requiresAuth: true, tipoDeUsuarioPermitido: 'cliente' } 
  }
]

const roteador = createRouter({
  history: createWebHistory(),
  routes: rotasDasPaginas
})

// Guarda de Rota (beforeEach) - Controla acesso baseado no perfil do usuário logado
roteador.beforeEach(async (rotaDeDestino, rotaDeOrigem, proximaAcao) => {
  const tokenDeAutenticacao = localStorage.getItem('token')

  // Se a rota destino exige autenticação
  if (rotaDeDestino.meta.requiresAuth) {
    if (!tokenDeAutenticacao) {
      // Sem token, redireciona para a raiz de login
      return proximaAcao('/')
    }

    let tipoDeUsuario = localStorage.getItem('tipoUsuario')

    // Se temos token mas não temos tipoUsuario no localStorage, consultamos a API
    if (!tipoDeUsuario) {
      try {
        const respostaDoUsuario = await api.get('/me')
        tipoDeUsuario = respostaDoUsuario.data.tipo
        localStorage.setItem('tipoUsuario', tipoDeUsuario)
      } catch (erroAoBuscarUsuario) {
        // Se a requisição de busca falhar (token expirado/inválido), limpa credenciais e desloga
        localStorage.removeItem('token')
        localStorage.removeItem('tipoUsuario')
        return proximaAcao('/')
      }
    }

    const tipoPermitido = rotaDeDestino.meta.tipoDeUsuarioPermitido

    if (tipoPermitido && tipoDeUsuario !== tipoPermitido) {
      // Redirecionamento de segurança caso o tipo de usuário não corresponda ao permitido para a rota
      if (tipoDeUsuario === 'entregador') {
        return proximaAcao('/painel-entregador')
      } else if (tipoDeUsuario === 'lojista') {
        return proximaAcao('/dashboard-lojista')
      } else if (tipoDeUsuario === 'cliente') {
        return proximaAcao('/home')
      }
    }

    // Se o tipo do usuário for compatível ou a rota não possuir restrição de tipo, permite o fluxo
    return proximaAcao()
  } else {
    // Para rotas públicas:
    // Se o usuário já estiver logado e tentar acessar a tela de Login ou Cadastro
    const paginasDeAutenticacao = ['/', '/login', '/cadastro']
    if (tokenDeAutenticacao && paginasDeAutenticacao.includes(rotaDeDestino.path)) {
      let tipoDeUsuario = localStorage.getItem('tipoUsuario')

      if (!tipoDeUsuario) {
        try {
          const respostaDoUsuario = await api.get('/me')
          tipoDeUsuario = respostaDoUsuario.data.tipo
          localStorage.setItem('tipoUsuario', tipoDeUsuario)
        } catch (erroAoBuscarUsuario) {
          localStorage.removeItem('token')
          localStorage.removeItem('tipoUsuario')
          return proximaAcao()
        }
      }

      if (tipoDeUsuario === 'entregador') {
        return proximaAcao('/painel-entregador')
      } else if (tipoDeUsuario === 'lojista') {
        return proximaAcao('/dashboard-lojista')
      } else {
        return proximaAcao('/home')
      }
    }

    // Caso contrário, apenas avança
    return proximaAcao()
  }
})

export default roteador