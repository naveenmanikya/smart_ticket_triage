import { createApp } from 'vue'
import App    from './App.vue'
import router from './router/index.js'

import './assets/css/main.css'
import './assets/css/header.css'
import './assets/css/tickets.css'
import './assets/css/dashboard.css'
import './assets/css/modals.css'

const app = createApp(App)
app.use(router)
app.mount('#app')
