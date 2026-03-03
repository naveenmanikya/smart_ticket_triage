<template>
  <span class="badge" :class="badgeClass">{{ label }}</span>
</template>

<script>
export default {
  name: 'TicketBadge',
  props: {
    category: {
      type: String,
      default: null,
    },
  },
  computed: {
    label() {
      return this.category || 'Unclassified'
    },
    badgeClass() {
      const slug = this.category
        ? this.category.toLowerCase().replace(/\s/g, '-')
        : 'unclassified'
      return `badge--${slug}`
    },
  },
}
</script>
