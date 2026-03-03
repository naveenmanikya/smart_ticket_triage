<template>
  <div class="app-container">
    <AppHeader :theme="theme" @toggle-theme="toggleTheme" />

    <main class="main-content">
      <router-view
        :key="$route.path"
        :refresh-key="ticketRefreshKey"
        @show-new-ticket-modal="openNewTicketModal"
        @show-ticket-detail-modal="openTicketDetailModal"
      />
    </main>

    <!-- New Ticket Modal -->
    <NewTicketModal
      v-if="isNewTicketModalVisible"
      @close="closeModal"
      @submitted="handleTicketSubmitted"
    />

    <!-- Ticket Detail Modal -->
    <TicketDetailModal
      v-if="isTicketDetailModalVisible && selectedTicket"
      :ticket="selectedTicket"
      @close="closeModal"
      @update="handleTicketUpdate"
    />
  </div>
</template>

<script>
import AppHeader from './components/AppHeader.vue'
import NewTicketModal   from './modals/NewTicketModal.vue'
import TicketDetailModal from './modals/TicketDetailModal.vue'
import { createTicket, updateTicket } from './api/ticketApi.js'

export default {
  name: 'App',
  components: { AppHeader, NewTicketModal, TicketDetailModal },
  data() {
    return {
      isNewTicketModalVisible: false,
      isTicketDetailModalVisible: false,
      selectedTicket: null,
      theme: 'light',
      ticketRefreshKey: 0,
    }
  },
  mounted() {
    const savedTheme = localStorage.getItem('theme') || 'light'
    this.theme = savedTheme
    document.body.setAttribute('data-theme', savedTheme)
  },
  methods: {
    toggleTheme() {
      this.theme = this.theme === 'light' ? 'dark' : 'light'
      document.body.setAttribute('data-theme', this.theme)
      localStorage.setItem('theme', this.theme)
    },
    openNewTicketModal() {
      this.isNewTicketModalVisible = true
    },
    openTicketDetailModal(ticket) {
      this.selectedTicket = { ...ticket }
      this.isTicketDetailModalVisible = true
    },
    closeModal() {
      this.isNewTicketModalVisible = false
      this.isTicketDetailModalVisible = false
      this.selectedTicket = null
    },
    async handleTicketSubmitted(formData) {
      try {
        await createTicket(formData)
        this.closeModal()
        this.ticketRefreshKey++
      } catch (error) {
        console.error('Error creating ticket:', error)
      }
    },
    async handleTicketUpdate({ id, category, note }) {
      try {
        await updateTicket(id, { category, note })
        this.ticketRefreshKey++
      } catch (error) {
        console.error('Error updating ticket:', error)
      }
    },
  },
}
</script>
