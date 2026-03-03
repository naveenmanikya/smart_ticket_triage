<template>
  <header class="header">
    <h1 class="header__title">Smart Ticket Triage</h1>
    <nav class="header__nav">
      <router-link to="/tickets" class="header__nav-link">Tickets</router-link>
      <router-link to="/dashboard" class="header__nav-link">Dashboard</router-link>
      <button @click="$emit('toggle-theme')" class="button button--theme-toggle">
        {{ theme === 'light' ? '🌙 Dark Mode' : '☀️ Light Mode' }}
      </button>
    </nav>
  </header>
</template>

<script>
export default {
  name: 'AppHeader',
  props: {
    theme: {
      type: String,
      default: 'light',
    },
  },
  emits: ['toggle-theme'],
}
</script>
