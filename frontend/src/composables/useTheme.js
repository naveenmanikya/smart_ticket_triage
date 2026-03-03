import { ref, onMounted } from 'vue'

export function useTheme() {
  const theme = ref('light')

  function toggleTheme() {
    theme.value = theme.value === 'light' ? 'dark' : 'light'
    document.body.setAttribute('data-theme', theme.value)
    localStorage.setItem('theme', theme.value)
  }

  onMounted(() => {
    const saved = localStorage.getItem('theme') || 'light'
    theme.value = saved
    document.body.setAttribute('data-theme', saved)
  })

  return { theme, toggleTheme }
}