<template>
  <div class="booking-list">
    <booking
      v-for="booking in bookingList"
      :key="booking.id"
      :booking="booking"
    />
  </div>
</template>
<script>
import Booking from './Booking.vue'
export default {
  components: {
    Booking
  },
  props: {
    bookingList: {
      type: Array,
      required: false,
      default: () => []
    }
  },
  data() {
    return {}
  }
}
</script>
