<template>
  <div class="modal-overlay" @click.self="$emit('close')">
    <div class="modal">
      <div class="modal__header">
        <h2 class="modal__title">Create New Ticket</h2>
        <button class="modal__close-button" @click="$emit('close')">&times;</button>
      </div>
      <div class="modal__body">
        <form @submit.prevent="handleSubmit" class="form">
          <div class="form__group">
            <label for="new-subject" class="form__label">Subject</label>
            <input
              id="new-subject"
              type="text"
              class="form__input"
              v-model="form.subject"
              required
            />
          </div>
          <div class="form__group">
            <label for="new-body" class="form__label">Body</label>
            <textarea
              id="new-body"
              class="form__textarea"
              v-model="form.body"
              required
            />
          </div>
          <div class="form__actions">
            <button type="submit" class="button button--primary" :disabled="isSubmitting">
              <span v-if="isSubmitting">Submitting...</span>
              <span v-else>Submit Ticket</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'NewTicketModal',
  emits: ['close', 'submitted'],
  data() {
    return {
      isSubmitting: false,
      form: { subject: '', body: '' },
    }
  },
  methods: {
    async handleSubmit() {
      this.isSubmitting = true
      try {
        this.$emit('submitted', { ...this.form })
        this.form.subject = ''
        this.form.body = ''
      } finally {
        this.isSubmitting = false
      }
    },
  },
}
</script>
