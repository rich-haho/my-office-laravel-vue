<template>
  <el-dialog :title="title" :visible="show" width="30%" @close="onClose">
    <span slot="footer" class="dialog-footer">
      <el-row>
        <el-col>
          <el-button
            :loading="deactivateLoading"
            :type="buttonType"
            @click="onDeactivate"
          >
            {{ buttonText }}
          </el-button>
          <el-button type="text" @click="onClose">
            {{ $t('actions.cancel') }}</el-button
          >
        </el-col>
      </el-row>
    </span>
  </el-dialog>
</template>

<script>
export default {
  name: 'Deactivate',
  props: {
    title: {
      type: String,
      default: 'Veuillez confirmer la désactivation'
    },
    buttonText: {
      type: String,
      default: 'Désactiver'
    },
    buttonType: {
      type: String,
      default: 'danger'
    },
    show: {
      type: Boolean,
      default: false
    },
    onDeactivate: {
      type: Function,
      default: () => {}
    },
    onClose: {
      type: Function,
      default: () => {}
    },
    deactivateLoading: {
      type: Boolean,
      default: false
    }
  }
}
</script>
