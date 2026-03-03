import { createRouter, createWebHistory } from 'vue-router'
import TicketsView   from '../views/TicketsView.vue'
import DashboardView from '../views/DashboardView.vue'

const routes = [
  { path: '/',          redirect: '/tickets' },
  { path: '/tickets',   component: TicketsView,   name: 'tickets' },
  { path: '/dashboard', component: DashboardView, name: 'dashboard' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
