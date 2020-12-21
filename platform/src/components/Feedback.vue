<template>
  <el-card class="box-card feedback">
    <div class="feedback-title">
      <stars :current="rate" />
      <div>
        <span> {{ $t('components.feedback') }}</span> <br />
        <span class="feedback-time"> {{ ratedBy }} {{ timeAgo }}</span>
      </div>
    </div>
    <p class="feedback-content">
      {{ content }}
    </p>
  </el-card>
</template>

<script>
import Stars from './Stars'
export default {
  name: 'Feedback',
  components: { Stars },
  props: {
    rate: { type: Number, default: 0 },
    content: { type: String, default: '' },
    ratedBy: { type: String, default: '' },
    timeAgo: { type: String, default: '' }
  }
}
</script>

<style scoped lang="scss">
.feedback {
  .feedback-title {
    display: flex;
    margin-bottom: 20px;
    & > span {
      margin-left: 10px;
    }
  }
  .feedback-time {
    font-size: 11px;
    color: gray;
  }
}
</style>
