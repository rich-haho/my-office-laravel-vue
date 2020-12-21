<template>
  <el-dialog :title="title" :visible="show" width="30%" @close="onClose">
    <span slot="footer" class="dialog-footer">
      <el-row>
        <el-col>
          <el-button :loading="deleteLoading" type="danger" @click="onDelete">
            {{ $t('components.removal_confirm') }}
          </el-button>
          <el-button type="text" class="mt-10" @click="onClose">
            {{ $t('actions.cancel') }}
          </el-button>
        </el-col>
      </el-row>
    </span>
  </el-dialog>
</template>

<script>
export default {
  name: 'Removal',
  props: {
    title: {
      type: String,
      default: 'Veuillez confirmer la suppression'
    },
    show: {
      type: Boolean,
      default: false
    },
    onDelete: {
      type: Function,
      default: () => {}
    },
    onClose: {
      type: Function,
      default: () => {}
    },
    deleteLoading: {
      type: Boolean,
      default: false
    }
  }
}
</script>
