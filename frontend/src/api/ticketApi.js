const API_BASE_URL = 'http://127.0.0.1:8000/api'

export async function fetchTickets(params) {
  const url = new URL(`${API_BASE_URL}/tickets`)
  Object.keys(params).forEach(key => {
    if (params[key]) url.searchParams.append(key, params[key])
  })
  const response = await fetch(url.toString())
  if (!response.ok) throw new Error('Failed to fetch tickets')
  return response.json()
}

export async function fetchStats() {
  const response = await fetch(`${API_BASE_URL}/stats`)
  if (!response.ok) throw new Error('Failed to fetch stats')
  return response.json()
}

export async function fetchTicketById(id) {
  const response = await fetch(`${API_BASE_URL}/tickets/${id}`)
  if (!response.ok) throw new Error('Failed to fetch ticket')
  return response.json()
}

export async function classifyTicket(id) {
  const response = await fetch(`${API_BASE_URL}/tickets/${id}/classify`, {
    method: 'POST',
  })
  if (!response.ok) throw new Error('Failed to classify ticket')

  const maxRetries = 10
  const delay = 2000

  for (let i = 0; i < maxRetries; i++) {
    await new Promise(resolve => setTimeout(resolve, delay))
    const ticket = await fetchTicketById(id)
    if (ticket.category && ticket.confidence != null) break
  }

  return response.json()
}

export async function createTicket(ticketData) {
  const response = await fetch(`${API_BASE_URL}/tickets`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(ticketData),
  })
  if (!response.ok) throw new Error('Failed to create ticket')
  return response.json()
}

export async function updateTicket(id, data) {
  const response = await fetch(`${API_BASE_URL}/tickets/${id}`, {
    method: 'PATCH',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data),
  })
  if (!response.ok) throw new Error('Failed to update ticket')
  return response.json()
}
