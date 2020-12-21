export default {
  listLoading(state) {
    state.listLoading = true
    state.listError = null
  },
  listSuccess(state, bookinglist) {
    state.bookinglist = bookinglist
    state.listLoading = false
    state.listError = null
  },
  listError(state, error) {
    state.listLoading = false
    state.listError = error
  },
  deleteLoading(state) {
    state.deleteLoading = true
    state.deleteError = null
  },
  deleteSuccess(state, id) {
    state.deleteLoading = false
    state.bookinglist.splice(
      state.bookinglist.findIndex(item => item.id === id),
      1
    )
    state.deleteError = null
  },
  deleteError(state, error) {
    state.deleteLoading = false
    state.deleteError = error
  },

  saveLoading(state) {
    state.saveLoading = true
    state.saveError = null
  },
  saveSuccess(state, booking) {
    state.saveLoading = false
    state.bookinglist.data = [...state.bookinglist.data, booking]
    state.saveError = null
  },
  saveError(state, error) {
    state.saveLoading = false
    state.saveError = error
  },

  getBookingLoading(state) {
    state.getBookingLoading = true
    state.getBookingError = null
  },
  getBookingSuccess(state, item) {
    state.getBookingLoading = false
    state.item = item
    state.getBookingError = null
  },
  getBookingError(state, error) {
    state.getBookingLoading = false
    state.getBookingError = error
  },
  clearBooking(state) {
    state.getBookingLoading = false
    state.getBookingError = null
    state.item = {}
  },
  getBookingStatusesLoading(state) {
    state.getBookingStatusesLoading = true
    state.getBookingStatusesError = null
  },
  getBookingStatusesSuccess(state, statuses) {
    state.getBookingStatusesLoading = false
    state.statuses = statuses
    state.getBookingStatusesError = null
  },
  getBookingStatusesError(state, error) {
    state.getBookingStatusesLoading = false
    state.getBookingStatusesError = error
  }
}
