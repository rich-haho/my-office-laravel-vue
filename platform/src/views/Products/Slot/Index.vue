<template>
  <el-card class="box-card" :class="displayError()">
    <el-row :gutter="10" type="flex" justify="end">
      <el-col :xs="10" :sm="4" :md="3">
        <el-button
          type="danger"
          icon="el-icon-delete"
          @click.prevent="removeSlot"
        />
      </el-col>
    </el-row>

    <el-form-item
      :error="errorMessage(saveError, 'slots.' + this.$vnode.key + '.days')"
    >
      <el-checkbox
        v-for="day in days"
        :key="day"
        v-model="convertDays"
        :label="day"
      />
    </el-form-item>

    <el-divider class="mt-20 mb-5" />
    <el-button
      type="primary"
      icon="el-icon-time"
      size="small"
      @click="addTimeSelect(productSlot)"
      >Ajouter horaire</el-button
    >

    <el-form-item v-for="(time, index) in sortTimes" :key="time.id">
      <slot-time :key="index" :times="productSlot.times" :time="time" />
    </el-form-item>

    <el-row :gutter="10">
      <el-col :xs="24" :sm="22">
        <p>Période de validité</p>
        <el-date-picker
          v-model="convertDate"
          type="daterange"
          format="dd / MM / yyyy"
          value-format="yyyy-MM-dd"
          start-placeholder="Date début"
          end-placeholder="Date fin"
          class="date-select"
          :picker-options="pickerOptions"
        >
        </el-date-picker>
      </el-col>
    </el-row>

    <el-row :gutter="10">
      <el-col :xs="24" :sm="11">
        <p>Minimum de participant</p>
        <el-input-number
          v-model="productSlot.min_participant"
          :min="0"
          :max="productSlot.max_participant"
          label="Min"
          size="small"
        />
      </el-col>
      <el-col :xs="24" :sm="11">
        <p>Maximum de participant</p>
        <el-input-number
          v-model="productSlot.max_participant"
          :min="productSlot.min_participant || 1"
          label="Max"
          size="small"
        />
      </el-col>
    </el-row>
  </el-card>
</template>

<script>
import { mapState } from 'vuex'
import { errorMessage } from '@/services/api'
import SlotTime from '@/views/Products/Slot/Time'
export default {
  name: 'ProductSlot',
  components: { SlotTime },
  props: {
    productSlot: {
      required: true,
      type: Object
    },
    productSlots: {
      required: true,
      type: Array
    }
  },
  data() {
    return {
      days: [
        'Lundi',
        'Mardi',
        'Mercredi',
        'Jeudi',
        'Vendredi',
        'Samedi',
        'Dimanche'
      ],
      pickerOptions: {
        firstDayOfWeek: 1
      }
    }
  },
  computed: {
    ...mapState('products', ['item', 'saveError']),
    convertDays: {
      get: function() {
        let result = []

        for (let i = 0; i < this.days.length; i++) {
          if (this.productSlot.days[i] === '1') {
            result.push(this.days[i])
          }
        }
        return result
      },
      set: function(newValue) {
        let newDays = ''
        let check

        for (let i = 0; i < this.days.length; i++) {
          check = false
          for (let j = 0; j < newValue.length; j++) {
            if (this.days[i] === newValue[j]) {
              check = true
              newDays += '1'
            }
          }
          if (!check) {
            newDays += '0'
          }
        }
        this.productSlot.days = newDays
      }
    },
    convertDate: {
      get: function() {
        let result = []

        if (this.productSlot.start_date && this.productSlot.end_date) {
          result = [this.productSlot.start_date, this.productSlot.end_date]
        }
        return result
      },
      set: function(newValue) {
        this.productSlot.start_date = Array.isArray(newValue)
          ? newValue[0]
          : null
        this.productSlot.end_date = Array.isArray(newValue) ? newValue[1] : null
      }
    },
    sortTimes: {
      get: function() {
        let sortValue = this.productSlot.times

        sortValue.sort(this.sortTime)
        return sortValue
      }
    }
  },
  methods: {
    errorMessage,
    addTimeSelect() {
      this.productSlot.times.push({
        keyTimeSelect: Date.now(),
        startTime: '',
        endTime: ''
      })
    },
    removeSlot() {
      const index = this.$vnode.key

      this.productSlots.splice(index, 1)
    },
    sortTime(a, b) {
      if (a.start_time > b.start_time) {
        return 1
      } else if (a.start_time < b.start_time) {
        return -1
      } else {
        return 0
      }
    },
    displayError() {
      if (this.saveError) {
        if (this.saveError.errors[`slots.${this.$vnode.key}.days`] != null) {
          return 'box-card-error'
        }
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';
.date-select,
.el-input-number {
  width: 100% !important;
}
.box-card-error {
  border-color: $color-red;
}
</style>
