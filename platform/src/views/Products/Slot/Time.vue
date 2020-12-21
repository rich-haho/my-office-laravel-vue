<template>
  <el-row
    :gutter="10"
    type="flex"
    align="middle"
    justify="center"
    class="row-wrap"
  >
    <el-col :xs="24" :sm="11">
      <el-time-select
        v-model="time.start_time"
        placeholder="Heure début"
        class="mr-5 mt-5 time-select"
        :picker-options="{
          start: '07:00',
          end: '22:00',
          step: '00:10',
          maxTime: time.end_time
        }"
      >
      </el-time-select>
    </el-col>
    <el-col :xs="20" :sm="11">
      <el-time-select
        v-model="time.end_time"
        placeholder="Heure fin"
        class="mt-5 time-select"
        :picker-options="{
          start: '07:00',
          end: '22:00',
          step: '00:10',
          minTime: time.start_time
        }"
      >
      </el-time-select>
    </el-col>
    <el-col :xs="4" :sm="2" class="delete-button">
      <el-button
        type="danger"
        icon="el-icon-delete"
        size="small"
        plain
        @click="removeTimeSelect"
      />
    </el-col>
  </el-row>
</template>

<script>
import { mapState } from 'vuex'
export default {
  name: 'SlotTime',
  props: {
    time: {
      required: true,
      type: Object
    },
    times: {
      required: true,
      type: Array
    }
  },
  computed: {
    ...mapState('products', ['item'])
  },
  methods: {
    removeTimeSelect() {
      const index = this.$vnode.key

      this.times.splice(index, 1)
    }
  }
}
</script>

<style lang="scss" scoped>
.time-select {
  width: 100% !important;
}
.delete-button {
  text-align: center;
}
.row-wrap {
  flex-wrap: wrap;
}
</style>
