import { createRouter, createWebHistory } from 'vue-router'
import Login from '../views/Login.vue'
import Selection from '../views/LoginSelection.vue'
import Register from '../views/Register.vue'
import HomeCliente from '../views/HomeCliente.vue'

const routes = [
  { path: '/', component: Login },
  { path: '/login', component: Login },
  { path: '/selecao', component: Selection },
  { path: '/cadastro', component: Register },
  { path: '/home', component: HomeCliente },
  { path: '/esqueci-senha', component: () => import('../views/ForgotPassword.vue') },
    { path: '/dashboard-lojista', component: () => import('../views/LojistaDashboard.vue') },
  { path: '/painel-entregador', component: () => import('../views/EntregadorPainel.vue') },
  { path: '/meus-pedidos', component: () => import('../views/ClientePainel.vue') }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router