import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router' // Importa a configuração que você criou

const app = createApp(App)

app.use(router) // Diz ao Vue para usar o roteador
app.mount('#app')