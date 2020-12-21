<template>
  <div v-if="item.name !== undefined" class="global-padding">
    <el-row :gutter="20" class="product mb-30">
      <el-col v-if="$route.query.reason === 'cancel'" :span="24">
        <el-alert :title="$t('products.booking_cancel')" type="error" show-icon>
        </el-alert>
      </el-col>
      <el-col :xs="24" :sm="12" :md="12" :lg="7">
        <div class="product--image_preview">
          <el-image
            :src="item.assets ? item.assets.full_path : null"
            alt="image_preview"
            :preview-src-list="item.assets ? [item.assets.full_path] : []"
          >
            <div slot="error" class="image-slot">
              <div class="image-slot-error">
                <i class="el-icon-picture-outline"></i>
              </div>
            </div>
          </el-image>
        </div>
      </el-col>
      <el-col :xs="24" :sm="12" :md="12" :lg="11">
        <div class="product--information">
          <h2>{{ item.name }}</h2>
          <div
            v-if="item.ratings.length > 0"
            class="product--information-feedback"
          >
            <stars :current="averageRate(item.ratings)" />
            <span>{{ item.ratings.length }} {{ $t('products.views') }}</span>
          </div>
          <p class="product--information-description">
            {{ item.description }}
          </p>
          <p class="product--information-more">
            {{ item.partner.public_description }}
          </p>
          <div v-if="item.slots.length" class="product--information-slots">
            <el-button
              v-show="!isPickerVisible"
              type="primary"
              plain
              @click="handleButton"
              >{{ $t('products.date_slot') }}</el-button
            >
            <el-col :sm="24" :md="12">
              <transition name="fade">
                <el-date-picker
                  v-show="isPickerVisible"
                  ref="datePicker"
                  v-model="date"
                  type="date"
                  :placeholder="$t('products.date_slot')"
                  format="dd / MM / yyyy"
                  :picker-options="pickerOptions"
                  @change="handleDatePicker(date)"
                >
                </el-date-picker>
              </transition>
            </el-col>
            <el-col :sm="24" :md="12">
              <transition name="fade">
                <el-select
                  v-if="selectValues.length"
                  v-model="time"
                  :placeholder="$t('products.select_time')"
                  @change="handleSelectTime"
                >
                  <el-option
                    v-for="(value, index) in selectValues"
                    :key="index"
                    :label="value.label"
                    :value="[value.id, value.label, value.available]"
                  />
                </el-select>
              </transition>
            </el-col>
          </div>
        </div>
      </el-col>
      <el-col :xs="24" :sm="24" :md="24" :lg="6">
        <div class="product--order">
          <prices
            :old-price="parseFloat(item.price_reduction) || 0"
            :real-price="parseFloat(item.price) || 0"
            :currency="item.currency.name"
          />
          <el-input-number
            v-model="quantity"
            :min="1"
            :max="item.slots.length ? spaceAvailable : Infinity"
          />
          <el-button
            :disabled="item.slots.length ? !isCompleted : false"
            type="primary"
            :loading="waitingForPayment"
            @click="accpetTermService"
          >
            {{ $t('products.order') }}
          </el-button>
          <p v-if="item.manual_product" class="manual-product">
            <i class="el-icon-warning" />
            {{ $t('products.manual_product') }}
          </p>
          <contact class="mt-25" />
        </div>
      </el-col>
    </el-row>

    <el-row v-if="needRate" :gutter="20" class="feedbacks">
      <el-col :lg="10" :md="12" :sm="12" :xs="16">
        <el-card class="box-card">
          <h3 class="text-center">{{ $t('products.rate') }}</h3>
          <el-form @submit.native.prevent>
            <el-form-item :error="errorMessage(saveRatingError, 'rate')">
              <el-rate
                v-model="rating.rate"
                class="text-center mb-20 mt-20"
              ></el-rate>
            </el-form-item>
            <el-button
              :loading="saveRatingLoading"
              type="success"
              @click="rate"
            >
              {{ $t('products.save_rating') }}
            </el-button>
          </el-form>
        </el-card>
      </el-col>
    </el-row>

    <h4 class="mt-50 mb-20">{{ $t('products.product_interest') }}:</h4>
    <el-row v-loading="linkedProductsLoading" :gutter="20" class="feedbacks">
      <el-col v-for="prod in likedProducts" :key="prod.id" :md="12">
        <product :product="prod" :with-favorite="false" />
      </el-col>
    </el-row>
    <el-dialog
      :visible="showTermModal"
      class="termModal"
      :width="dialogWidth"
      @close="onCloseTermModal"
    >
      <el-row>
        <el-col class="mb-20">
          <el-checkbox v-model="isAceptetTerm">
            {{ $t('bookings.terms_label') }}
            <a href="/terms" target="_blank">{{ $t('bookings.terms_link') }}</a>
          </el-checkbox>
        </el-col>
        <el-col>
          <el-button type="success" @click="processPayment">
            {{ $t('products.order') }}
          </el-button>
        </el-col>
      </el-row>
    </el-dialog>
  </div>
</template>

<script>
import { mapActions, mapState, mapGetters } from 'vuex'
import Stars from '@/components/Stars'
import Prices from '@/components/Prices'
import Contact from '@/components/Contact'
import Product from '@/components/products/Product'
import api from '@/services/api'
import moment from 'moment'
import { errorMessage } from '@/services/api'

export default {
  name: 'ProductIndex',
  components: { Product, Contact, Prices, Stars },
  data() {
    return {
      quantity: 1,
      waitingForPayment: false,
      pickerOptions: {
        firstDayOfWeek: 1,
        disabledDate: this.timeFilter
      },
      date: '',
      time: '',
      spaceAvailable: 0,
      slotId: 0,
      selectValues: [],
      isPickerVisible: false,
      isCompleted: false,
      rating: {},
      showTermModal: false,
      isAceptetTerm: false
    }
  },
  computed: {
    ...mapState('products', [
      'item',
      'likedProducts',
      'linkedProductsLoading',
      'getProductError',
      'saveRatingLoading',
      'saveRatingError'
    ]),
    ...mapState('auth', ['user']),
    ...mapGetters('auth', ['site_id']),
    needRate() {
      let delivered = this.item.bookingProducts.findIndex(
        e => e.status === 'delivered'
      )
      let rated = this.item.ratings.findIndex(e => e.user_id === this.user.id)
      return rated === -1 && delivered > -1
    },
    dialogWidth: () => {
      if (window.matchMedia('(max-width: 768px)').matches) {
        return '95%'
      } else {
        return '50%'
      }
    }
  },
  watch: {
    '$route.path': 'loadData'
  },
  mounted() {
    this.loadData()
  },
  methods: {
    ...mapActions('products', [
      'getProduct',
      'getLinkedProducts',
      'saveRating'
    ]),
    errorMessage,
    async loadData() {
      await this.getProduct({ id: this.$route.params.id })
      await this.getLinkedProducts({ id: this.$route.params.id })
    },
    accpetTermService() {
      this.isAceptetTerm = false
      this.showTermModal = true
    },
    onCloseTermModal() {
      this.showTermModal = false
    },
    async processPayment() {
      this.waitingForPayment = true
      this.showTermModal = false
      // Get SessionID from API

      let response = null
      try {
        response = await api.post(
          '/products/' + this.$route.params.id + '/payment',
          {
            quantity: this.quantity,
            terms: this.isAceptetTerm,
            metadata: {
              site_id: this.site_id,
              slot_id: this.slotId ? this.slotId : null,
              date: this.date ? moment(this.date).format('YYYY-MM-DD') : null,
              start_time: this.time
                ? this.time[1] !== this.$t('products.slot_all_day')
                  ? this.time[1].split(' ')[0]
                  : null
                : null,
              end_time: this.time
                ? this.time[1] !== this.$t('products.slot_all_day')
                  ? this.time[1].split(' ')[2]
                  : null
                : null
            }
          }
        )
      } catch (e) {
        this.waitingForPayment = false
        return
      }

      /* eslint-disable */
      const stripe = Stripe(this.item.currency.pk)
      /* eslint-enable */

      await stripe.redirectToCheckout({
        // Make the id field from the Checkout Session creation API response
        // available to this file, so you can provide it as parameter here
        // instead of the {{CHECKOUT_SESSION_ID}} placeholder.
        sessionId: response.data
      })
      this.waitingForPayment = false
    },
    async handleButton() {
      this.isPickerVisible = !this.isPickerVisible
      await this.$nextTick()
      this.$refs.datePicker.focus()
    },
    handleDatePicker(value) {
      const { slots } = this.item
      const valueDate = moment(value).format('YYYY-MM-DD')
      let counter = 0

      this.slotId = 0
      this.spaceAvailable = 0
      this.selectValues = []

      if (value) {
        slots.map(slot => {
          const filteredTimes = slot.times
            .filter(() => !this.dateFilter(value, slot))
            .filter(({ start_time }) =>
              this.currentTimeFilter(value, start_time)
            )
          if (
            slot.space_available[valueDate] &&
            Object.keys(slot.space_available[valueDate]).length === 0
          ) {
            counter++
            this.spaceAvailable = slot.space_available[valueDate]
          } else if (filteredTimes.length >= 1) {
            counter++
            this.spaceAvailable = 0
            if (slot.space_available[valueDate]) {
              this.selectValues.push(
                ...filteredTimes.map(({ start_time, end_time }) => ({
                  id: slot.id,
                  label: `${start_time} - ${end_time}`,
                  available:
                    slot.space_available[valueDate][
                      start_time + ' - ' + end_time
                    ] || slot.max_participant
                }))
              )
            } else {
              this.selectValues.push(
                ...filteredTimes.map(({ start_time, end_time }) => ({
                  id: slot.id,
                  label: `${start_time} - ${end_time}`,
                  available: slot.max_participant
                }))
              )
            }
          }
          if (this.allDay(value, slot)) {
            this.slotId = slot.id
            this.selectValues.push({
              id: slot.id,
              label: this.$t('products.slot_all_day'),
              available:
                this.spaceAvailable !== 0
                  ? this.spaceAvailable
                  : slot.max_participant
            })
          }
          if (counter === 0) {
            this.spaceAvailable = slot.max_participant
          }
        })
        if (
          this.selectValues.length === 1 &&
          this.selectValues[0].label === this.$t('products.slot_all_day')
        ) {
          this.selectValues = []
        }
        this.time = ''
        this.selectValues.sort(this.sortSelectTime)
      }
      this.quantity = 1
      this.isCompleted = !!(this.selectValues.length === 0 && this.date)
    },
    timeFilter(time) {
      return this.item.slots.every(slot => this.dateFilter(time, slot))
    },
    currentTimeFilter(time, start_time) {
      const timePicker = moment(time)
      const startTime = moment(start_time, 'HH:mm')

      return !(
        moment().isSame(timePicker, 'day') &&
        moment().isSameOrAfter(startTime, 'hour')
      )
    },
    dateFilter(time, slot) {
      const day = moment(time).isoWeekday()
      const timePicker = moment(time)
      const currentTime = moment().subtract(1, 'days')

      if (
        (slot.times.length > 0 &&
          !slot.times.some(({ start_time }) =>
            this.currentTimeFilter(time, start_time)
          )) ||
        slot.days[day - 1] === '0' ||
        !slot.space_available ||
        timePicker < currentTime
      ) {
        return true
      }
      if (slot.start_date) {
        const startDate = moment(slot.start_date)
        if (startDate.diff(timePicker) > 0) {
          return true
        }
      }
      if (slot.end_date) {
        const endDate = moment(slot.end_date)
        if (endDate.diff(timePicker) < 0) {
          return true
        }
      }
      return false
    },
    handleSelectTime(value) {
      this.spaceAvailable = value[2]
      this.slotId = value[0]
      this.isCompleted = !!this.time
      this.quantity = 1
    },
    allDay(time, slot) {
      return slot.times.length === 0 && !this.dateFilter(time, slot)
    },
    sortSelectTime(a, b) {
      if (a.label > b.label) {
        return 1
      } else if (a.label < b.label) {
        return -1
      }
      return 0
    },
    async rate() {
      await this.saveRating({
        product_id: this.item.id,
        ...this.rating
      })
    },
    averageRate(ratings) {
      let sum = 0
      ratings.map(item => {
        sum += item.rate
      })
      return Math.round((sum / ratings.length) * 10) / 10
    }
  }
}
</script>

<style scoped lang="scss">
@import '~@/assets/styles/_variables.scss';
.product {
  .el-col {
    margin-top: 10px;
  }
  .el-image {
    min-height: 200px;
    min-width: 100%;
  }
  .el-image__inner {
    cursor: pointer;
  }
  .product--image_preview {
    position: relative;
    display: flex;
    border-radius: 5px;
    border: 0.5px solid #e2e2e2;
    overflow: hidden;
    i {
      position: absolute;
      top: 5px;
      right: 10px;
    }
    img {
      object-fit: contain;
      width: 100%;
    }
  }
  .product--information {
    padding: 20px;
    .product--information-feedback {
      display: flex;
      & > * {
        margin-right: 15px;
      }
    }
    .product--information-description {
      white-space: pre-line;
      margin: 2.5em 0;
      font-weight: bold;
    }
    .product--information-more {
      white-space: pre-line;
      font-style: italic;
      color: lighten(black, 50%);
      font-size: smaller;
    }
    .product--information-slots {
      margin: 2.5em 0 0 0;
      & > .el-col > .el-input,
      .el-select,
      .el-select > .el-input {
        width: auto;
        display: block;
      }
    }
  }
  .product--order {
    display: flex;
    flex-direction: column;
    align-items: center;
    & > * {
      margin-bottom: 15px;
    }
  }
}

.feedbacks {
  display: flex;
  justify-content: center;

  .el-col {
    margin-bottom: 10px;
  }
}

.termModal {
  a {
    white-space: normal;
    color: $primary-color;
  }
}

.image-slot-error {
  min-height: 200px;
  min-width: 100%;
  font-size: 60px;
  background-color: #f5f7fa;
  text-align: center;
  i {
    color: #909399;
    left: 45%;
    top: 70px !important;
    right: 50% !important;
  }
}

.manual-product {
  font-style: italic;
  color: lighten(black, 50%);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>

<style lang="scss">
.termModal {
  .el-dialog__body {
    word-break: break-word !important;
  }
}
</style>
