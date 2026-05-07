import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Selection from '../views/LoginSelection.vue'
import Register from '../views/Register.vue'
import HomeCliente from '../views/HomeCliente.vue'

const routes = [
  // ROTAS PÚBLICAS (Qualquer um acessa)
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/selecao', component: Selection },
  { path: '/cadastro', component: Register },
  { path: '/esqueci-senha', component: () => import('../views/ForgotPassword.vue') },
  
  // ✨ NOVA ROTA: Redefinição de Senha (Pública)
  { 
    path: '/reset-password', 
    component: () => import('../views/ResetPassword.vue'),
    name: 'ResetPassword'
  },

  // ROTAS PROTEGIDAS (Exigem Token JWT)
  { 
    path: '/home', 
    component: HomeCliente,
    meta: { requiresAuth: true } 
  },
  // ✨ ROTA DO LOJISTA
  { 
    path: '/dashboard-lojista', 
    component: () => import('../views/DashboardLogista.vue'),
    meta: { requiresAuth: true } 
  },
  // ✨ ROTA DE PRODUTOS DO LOJISTA (A ROTA QUE FALTAVA!)
  { 
    path: '/lojista-produtos', 
    component: () => import('../views/LojistaProdutos.vue'),
    meta: { requiresAuth: true } 
  },
  // ✨ ROTA DO ENTREGADOR
  { 
    path: '/painel-entregador', 
    component: () => import('../views/EntregadorPainel.vue'),
    meta: { requiresAuth: true } 
  },
  { 
    path: '/lojista-perfil', 
    component: () => import('../views/LojistaPerfil.vue'),
    meta: { requiresAuth: true } 
  },
  { 
    path: '/meus-pedidos', 
    component: () => import('../views/MeusPedidos.vue'),
    meta: { requiresAuth: true } 
  },
  { 
    path: '/carrinho', 
    component: () => import('../views/Carrinho.vue'),
    meta: { requiresAuth: true } 
  },
  { 
    path: '/perfil', 
    component: () => import('../views/Perfil.vue'),
    meta: { requiresAuth: true } 
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// O SEGURANÇA DA BALADA (Guarda de Rota)
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('token')

  // REGRA 1: Tenta acessar rota protegida sem estar logado
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/') 
  } 
  // REGRA 2: Já está logado e tenta ver a tela de Login ou Cadastro (exceto Reset Password)
  else if ((to.path === '/' || to.path === '/login' || to.path === '/cadastro') && isAuthenticated) {
    next('/home')
  } 
  // REGRA 3: Tudo certo!
  else {
    next() 
  }
})

export default router