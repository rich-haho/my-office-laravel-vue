import api from '@/services/api'

export const send = async (
  { commit },
  { subject, description, contact, building }
) => {
  commit('sendLoading')
  try {
    await api.post('/fm-services', {
      subject,
      description,
      contact,
      building
    })
    commit('sendSuccess')
  } catch (e) {
    commit('sendError', e.response.data)
  }
}

export default {
  send
}
