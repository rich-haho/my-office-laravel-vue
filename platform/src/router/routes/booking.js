import BookingsList from '@/views/Bookings/List'
import BookingsSave from '@/views/Bookings/Save'

const routes = [
  {
    name: 'booking-list',
    path: 'bookings',
    component: BookingsList,
    meta: { layout: 'admin', guard: 'admin' }
  },
  {
    name: 'booking-save',
    path: 'bookings/save/:id?',
    component: BookingsSave,
    meta: { layout: 'admin', guard: 'admin' }
  }
]

export default routes
