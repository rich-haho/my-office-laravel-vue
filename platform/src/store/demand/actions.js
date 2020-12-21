import api from '@/services/api'

export const send = async (
  { commit },
  { category, subject, description, contact }
) => {
  commit('sendLoading')
  try {
    await api.post('/demand', {
      category,
      subject,
      description,
      contact
    })
    commit('sendSuccess')
  } catch (e) {
    commit('sendError', e.response.data)
  }
}

export default {
  send
}
