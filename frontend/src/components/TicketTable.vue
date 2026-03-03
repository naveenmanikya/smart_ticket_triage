<template>
  <div class="ticket-list">
    <div v-if="loading" class="ticket-list__message">Loading tickets...</div>
    <div v-else-if="tickets.length === 0" class="ticket-list__message">No tickets found.</div>
    <div v-else class="ticket-table">
      <div class="ticket-table__header">
        <div class="ticket-table__cell ticket-table__cell--category">Category</div>
        <div class="ticket-table__cell ticket-table__cell--subject">Subject</div>
        <div class="ticket-table__cell ticket-table__cell--confidence">Confidence</div>
        <div class="ticket-table__cell ticket-table__cell--actions">Actions</div>
      </div>
      <div
        v-for="ticket in tickets"
        :key="ticket.id"
        class="ticket-table__row"
      >
        <div class="ticket-table__cell ticket-table__cell--category">
          <TicketBadge :category="ticket.category" />
        </div>
        <div
          class="ticket-table__cell ticket-table__cell--subject"
          @click="$emit('view-ticket', ticket)"
        >
          {{ ticket.subject }}
          <span
            v-if="ticket.note"
            class="ticket-note-badge"
            title="Note added"
            v-html="noteIcon"
          />
        </div>
        <div class="ticket-table__cell ticket-table__cell--confidence">
          {{ ticket.confidence ? (ticket.confidence * 100).toFixed(0) + '%' : 'N/A' }}
        </div>
        <div class="ticket-table__cell ticket-table__cell--actions">
          <button
            class="button button--secondary"
            @click.stop="$emit('classify', ticket.id)"
            :disabled="classifyingIds[ticket.id]"
          >
            <span v-if="classifyingIds[ticket.id]" class="spinner" />
            <span v-else>Classify</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import TicketBadge from './TicketBadge.vue'

export default {
  name: 'TicketTable',
  components: { TicketBadge },
  props: {
    tickets: { type: Array, default: () => [] },
    loading: { type: Boolean, default: false },
    classifyingIds: { type: Object, default: () => ({}) },
  },
  emits: ['view-ticket', 'classify'],
  data() {
    return {
      noteIcon: `<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="12" y1="12" x2="16" y2="12"/></svg>`,
    }
  },
}
</script>
