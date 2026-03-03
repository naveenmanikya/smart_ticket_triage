<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal">
      <div class="modal__header">
        <h2 class="modal__title">Ticket #{{ ticket.id }}</h2>
        <button class="modal__close-button" @click="$emit('close')">&times;</button>
      </div>
      <div class="modal__body">

        <div class="detail-section">
          <h3 class="detail-section__title">Subject:</h3>
          <p class="detail-section__text">{{ ticket.subject }}</p>
        </div>

        <div class="detail-section">
          <h3 class="detail-section__title">Body:</h3>
          <p class="detail-section__text">{{ ticket.body }}</p>
        </div>

        <div class="detail-section">
          <h3 class="detail-section__title">AI Classification:</h3>
          <div class="detail-section__content detail-section__content--horizontal">
            <div class="form__group">
              <label class="form__label">Category</label>
              <select class="form__select" v-model="localTicket.category" @change="handleUpdate">
                <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
              </select>
            </div>
            <div class="form__group">
              <label class="form__label">Confidence</label>
              <p class="detail-section__readonly">
                {{ ticket.confidence ? (ticket.confidence * 100).toFixed(0) + '%' : 'N/A' }}
              </p>
            </div>
          </div>
        </div>

        <div class="detail-section">
          <h3 class="detail-section__title">Note:</h3>
          <textarea
            class="form__textarea"
            v-model="localTicket.note"
            @blur="handleUpdate"
          />
        </div>

        <div class="detail-section">
          <h3 class="detail-section__title">Explanation:</h3>
          <p class="detail-section__readonly">{{ ticket.explanation || 'N/A' }}</p>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TicketDetailModal',
  props: {
    ticket: { type: Object, required: true },
  },
  emits: ['close', 'update'],
  data() {
    return {
      localTicket: { ...this.ticket },
      categories: ['Billing', 'Technical', 'General Inquiry', 'Feature Request', 'Unclassified'],
    }
  },
  watch: {
    ticket(newVal) {
      this.localTicket = { ...newVal }
    },
  },
  methods: {
    handleUpdate() {
      this.$emit('update', {
        id: this.localTicket.id,
        category: this.localTicket.category,
        note: this.localTicket.note,
      })
    },
  },
}
</script>
