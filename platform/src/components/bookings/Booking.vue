<template>
  <div class="booking mb-25">
    <div class="image-container vertical-align">
      <el-image fit="contain" :src="booking.product.image" class="image" />
    </div>
    <div class="booking-detail-container pt-10 pb-10 pl-20 pr-20">
      <div class="booking-detail">
        <div class="booking-title">
          <el-row>
            <el-col :xs="24" :md="12">
              {{ booking.product.name }}
            </el-col>
            <el-col :xs="24" :md="12" class="text-right">
              {{ booking.date | datetime }}
            </el-col>
          </el-row>
        </div>
        <div class="mt-5 mb-5 booking-status">
          <strong>{{ $t('bookings.status.' + booking.status) }}</strong>
        </div>
        <div class="booking-time booking-slot">
          <span v-if="booking.product.date_slot">
            {{ $t('bookings.time_slot_label') }}
            {{ formatedDate(booking.product.date_slot) }}
          </span>
          <span v-if="booking.product.start_time && booking.product.end_time">
            {{ $t('bookings.time_slot_between') }}
            {{ booking.product.start_time.substring(0, 5) }}
            {{ $t('bookings.time_slot_and') }}
            {{ booking.product.end_time.substring(0, 5) }}
          </span>
        </div>
        <div class="booking-description">
          {{ booking.product.description }}
        </div>
        <div class="booking-description">
          {{ $t('bookings.quantity') }} : {{ booking.quantity }}
        </div>
      </div>
      <div class="booking-buttons mt-15 p-10 text-right">
        <span class="price">
          {{ (booking.product.price * booking.quantity).toFixed(2) }}
        </span>
        <span class="currency">{{ booking.product.currency }}</span>
      </div>
    </div>
  </div>
</template>
<script>
import moment from 'moment'

export default {
  props: {
    booking: {
      type: Object,
      required: true
    }
  },
  methods: {
    formatedDate(date) {
      return moment(date).format('DD/MM/YYYY')
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';
.booking {
  display: flex;
  @media screen and (max-width: $--xs) {
    display: block;
  }
  border-radius: 2px;
  box-shadow: 0 12px 36px 0 rgba(0, 0, 0, 0.2);
  background-color: #fff;
  align-items: stretch;
}
.image {
  background-color: $color-white;
  width: 150px;
  height: 100%;
  @media screen and (max-width: $--xs) {
    width: 100%;
    height: 250px;
  }
}
.price {
  font-size: 22px;
  color: $primary-color;
}
.currency {
  font-size: 22px;
  color: $color-light-grey;
  text-transform: uppercase;
}
.booking-status {
  text-align: justify;
  color: $color-blue;
}
.booking-detail-container {
  width: 100%;
  border-left: 1px solid $color-light-blue;
  display: flex;
  align-items: stretch;
  justify-content: stretch;
  flex-flow: column;
  .booking-detail {
    @media screen and (max-width: $--md) {
      justify-content: center;
    }
    .booking-title {
      font-size: 22px;
      font-weight: 300;
      letter-spacing: 0.33px;
    }
    .booking-description,
    .booking-time {
      text-align: justify;
      font-size: 14px;
      color: $color-text-grey;
      letter-spacing: 0.18px;
      &.booking-slot {
        font-weight: bold;
      }
    }
  }
  .booking-buttons {
    border-top: 1px solid $color-light-blue;
  }
}
</style>
