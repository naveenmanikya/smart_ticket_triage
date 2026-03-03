<template>
  <div class="dashboard-page">
    <div v-if="loading" class="ticket-list__message">Loading dashboard...</div>
    <div v-else class="dashboard-content">
      <div class="dashboard-cards">
        <div class="card">
          <h3 class="card__title">Total Tickets</h3>
          <p class="card__value">{{ stats.total_tickets }}</p>
        </div>
        <div class="card">
          <h3 class="card__title">Open Tickets</h3>
          <p class="card__value">{{ stats.status.open }}</p>
        </div>
        <div class="card">
          <h3 class="card__title">Closed Tickets</h3>
          <p class="card__value">{{ stats.status.closed }}</p>
        </div>
        <div class="card">
          <h3 class="card__title">In Progress</h3>
          <p class="card__value">{{ stats.status.in_progress }}</p>
        </div>
        <div class="card">
          <h3 class="card__title">Resolved</h3>
          <p class="card__value">{{ stats.status.resolved }}</p>
        </div>
      </div>
      <div class="chart-container">
        <canvas id="categoryChart"></canvas>
      </div>
    </div>
  </div>
</template>

<script>
import { nextTick } from 'vue'
import { fetchStats } from '../api/ticketApi.js'

export default {
  name: 'DashboardView',
  data() {
    return {
      stats: null,
      loading: true,
      chart: null,
    }
  },
  mounted() {
    this.loadStats()
  },
  methods: {
    async loadStats() {
      this.loading = true
      try {
        const apiStats = await fetchStats()
        this.stats = {
          total_tickets: apiStats.total_tickets,
          status: {
            open:        apiStats.open_tickets        ?? 0,
            closed:      apiStats.closed_tickets      ?? 0,
            in_progress: apiStats.in_progress_tickets ?? 0,
            resolved:    apiStats.resolved_tickets    ?? 0,
          },
          categories: (apiStats.tickets_by_category || []).reduce((acc, item) => {
            acc[item.category] = item.count
            return acc
          }, {}),
        }
        this.loading = false
        await nextTick()
        this.renderChart()
      } catch (error) {
        console.error('Error loading dashboard stats:', error)
      } finally {
        this.loading = false
      }
    },
    renderChart() {
      if (this.chart) {
        this.chart.destroy()
        this.chart = null
      }
      const canvas = document.getElementById('categoryChart')
      if (!canvas || !this.stats) return

      const ctx = canvas.getContext('2d')
      const categories = Object.keys(this.stats.categories)
      const data = Object.values(this.stats.categories)

      if (categories.length === 0) {
        categories.push('No Data')
        data.push(1)
      }

      const colors = ['#007bff', '#dc3545', '#28a745', '#fd7e14', '#6c757d', '#6f42c1', '#20c997']

      this.chart = new Chart(ctx, {
        type: 'pie',
        data: {
          labels: categories,
          datasets: [{ data, backgroundColor: colors.slice(0, categories.length) }],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: true, position: 'bottom' },
            tooltip: {
              callbacks: {
                label: ctx => `${ctx.label || ''}: ${ctx.raw || 0}`,
              },
            },
          },
        },
      })
    },
  },
}
</script>
