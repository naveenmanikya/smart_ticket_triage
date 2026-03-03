<template>
  <div class="tickets-page">
    <!-- Actions Bar -->
    <div class="ticket-actions">
      <div class="ticket-actions__filters">
        <input
          type="text"
          class="ticket-actions__search-input"
          v-model="searchText"
          placeholder="Search..."
          @input="loadTickets(1)"
        />
        <select
          class="ticket-actions__category-select"
          v-model="filterCategory"
          @change="loadTickets(1)"
        >
          <option value="">All Categories</option>
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>
      </div>
      <div class="ticket-actions__buttons">
        <button class="button button--secondary" @click="exportToCsv">Export to CSV</button>
        <button class="button button--primary" @click="$emit('show-new-ticket-modal')">New Ticket</button>
      </div>
    </div>

    <!-- Table -->
    <TicketTable
      :tickets="tickets"
      :loading="loading"
      :classifying-ids="isClassifying"
      @view-ticket="ticket => $emit('show-ticket-detail-modal', ticket)"
      @classify="runClassification"
    />

    <!-- Pagination -->
    <PaginationControls
      :current-page="currentPage"
      :total-pages="totalPages"
      @page-change="loadTickets"
    />
  </div>
</template>

<script>
import { reactive } from 'vue'
import { fetchTickets, classifyTicket } from '../api/ticketApi.js'
import TicketTable from '../components/TicketTable.vue'
import PaginationControls from '../components/PaginationControls.vue'

export default {
  name: 'TicketsView',
  components: { TicketTable, PaginationControls },
  props: {
    refreshKey: { type: Number, default: 0 },
  },
  emits: ['show-new-ticket-modal', 'show-ticket-detail-modal'],
  data() {
    return {
      tickets: [],
      currentPage: 1,
      pageSize: 10,
      totalPages: 1,
      filterCategory: '',
      searchText: '',
      loading: false,
      isClassifying: reactive({}),
      categories: ['Billing', 'Technical', 'General Inquiry', 'Feature Request', 'Unclassified'],
    }
  },
  mounted() {
    this.loadTickets(1)
  },
  watch: {
    refreshKey(newVal, oldVal) {
      if (newVal !== oldVal) this.loadTickets(1)
    },
  },
  methods: {
    async loadTickets(page = 1) {
      if (page instanceof Event) page = 1
      if (page < 1) page = 1
      if (this.totalPages && page > this.totalPages) page = this.totalPages

      this.loading = true
      try {
        const data = await fetchTickets({
          category: this.filterCategory,
          search: this.searchText,
          page,
          limit: this.pageSize,
        })
        this.tickets = data.data || []
        this.totalPages = data.last_page || 1
        this.currentPage = page
      } catch (error) {
        console.error('Error loading tickets:', error)
        this.tickets = []
        this.totalPages = 1
        this.currentPage = 1
      } finally {
        this.loading = false
      }
    },
    async runClassification(ticketId) {
      this.isClassifying[ticketId] = true
      try {
        await classifyTicket(ticketId)
        await this.loadTickets(1)
      } catch (error) {
        console.error('Error classifying ticket:', error)
      } finally {
        this.isClassifying[ticketId] = false
      }
    },
    exportToCsv() {
      if (!this.tickets.length) return
      const keys = ['id', 'subject', 'body', 'category', 'status', 'confidence', 'explanation', 'note']
      const rows = this.tickets.map(t => keys.map(k => t[k] ?? '').join(','))
      const csv = [keys.join(','), ...rows].join('\n')
      const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' })
      const link = document.createElement('a')
      link.href = URL.createObjectURL(blob)
      link.setAttribute('download', 'tickets_export.csv')
      link.style.visibility = 'hidden'
      document.body.appendChild(link)
      link.click()
      document.body.removeChild(link)
    },
  },
}
</script>
