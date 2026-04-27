import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Selection from '../views/LoginSelection.vue'
import Register from '../views/Register.vue'
import HomeCliente from '../views/HomeCliente.vue'

const routes = [
  //ROTAS PÚBLICAS (Qualquer um acessa)
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/selecao', component: Selection },
  { path: '/cadastro', component: Register },
  { path: '/esqueci-senha', component: () => import('../views/ForgotPassword.vue') },

  //ROTAS PROTEGIDAS (Exigem Token JWT)
  { 
    path: '/home', 
    component: HomeCliente,
    meta: { requiresAuth: true } 
  },
  { 
    path: '/dashboard-lojista', 
    component: () => import('../views/LojistaDashboard.vue'),
    meta: { requiresAuth: true } 
  },
  { 
    path: '/painel-entregador', 
    component: () => import('../views/EntregadorPainel.vue'),
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
  // Verifica no cofre do navegador se o usuário tem o Token do Laravel
  const isAuthenticated = localStorage.getItem('token')

  // REGRA 1: Tenta acessar rota protegida sem estar logado
  if (to.meta.requiresAuth && !isAuthenticated) {
    next('/') // Chuta de volta para o Login
  } 
  
// REGRA 2: Já está logado e tenta ver a tela de Login ou Cadastro de novo
  else if ((to.path === '/' || to.path === '/login' || to.path === '/cadastro') && isAuthenticated) {
    next('/home') // Redireciona direto para dentro do sistema
  } 

 
  // REGRA 3: Tudo certo, tem permissão!
  else {
    next() 
  }
})

export default router