<template>
  <div class="pagination-controls">
    <button
      class="button button--secondary"
      :disabled="currentPage === 1"
      @click="$emit('page-change', currentPage - 1)"
    >
      Previous
    </button>
    <span>Page {{ currentPage }} of {{ totalPages }}</span>
    <button
      class="button button--secondary"
      :disabled="currentPage === totalPages"
      @click="$emit('page-change', currentPage + 1)"
    >
      Next
    </button>
  </div>
</template>

<script>
export default {
  name: 'PaginationControls',
  props: {
    currentPage: { type: Number, required: true },
    totalPages:  { type: Number, required: true },
  },
  emits: ['page-change'],
}
</script>
