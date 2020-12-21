<template>
  <el-dialog
    :title="$t('auths.my_account')"
    :visible.sync="manageAccountVisible"
    :before-close="closeManageAccount"
    :width="dialogWidth"
    class="dialog-account"
  >
    <el-form>
      <el-form-item
        :label="$t('auths.name')"
        required
        :error="errorMessage(saveMeError, 'name')"
      >
        <el-input v-model="user.name" />
      </el-form-item>
      <el-form-item :label="$t('auths.update_password')">
        <el-switch v-model="user.updatepassword" @change="changeSwith">
        </el-switch>
      </el-form-item>
      <div v-if="user.updatepassword">
        <el-form-item
          :label="$t('auths.old_password')"
          required
          :error="errorMessage(saveMeError, 'password')"
        >
          <el-input v-model="user.password" type="password" />
        </el-form-item>
        <el-row>
          <el-col :span="24">
            <el-form-item
              :label="$t('auths.new_password')"
              required
              :error="errorMessage(saveMeError, 'new_password')"
            >
              <el-input v-model="user.new_password" type="password" />
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item
              :label="$t('auths.new_password_confirm')"
              required
              :error="errorMessage(saveMeError, 'new_password_confirmation')"
            >
              <el-input
                v-model="user.new_password_confirmation"
                type="password"
              />
            </el-form-item>
          </el-col>
        </el-row>
      </div>
      <el-row class="mt-20">
        <el-button type="primary" :loading="saveMeLoading" @click="saveUser">
          {{ $t('actions.record') }}
        </el-button>
      </el-row>
      <el-row class="mt-20">
        <el-button type="danger" @click="closeManageAccount">
          {{ $t('actions.cancel') }}
        </el-button>
      </el-row>
    </el-form>
  </el-dialog>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import { errorMessage } from '@/services/api'

export default {
  name: 'UserAccount',
  data() {
    return {
      user: {}
    }
  },

  computed: {
    dialogWidth: () => {
      if (window.matchMedia('(max-width: 768px)').matches) {
        return '95%'
      } else {
        return '50%'
      }
    },
    ...mapState('auth', [
      'manageAccountVisible',
      'saveMeError',
      'saveMeLoading'
    ]),
    ...mapGetters('auth', ['connectedUser'])
  },
  mounted: function() {
    this.user = this.connectedUser
  },
  methods: {
    ...mapActions('auth', ['logout', 'closeManageAccount', 'saveMe']),
    errorMessage,
    async saveUser() {
      await this.saveMe({
        ...this.user
      })
      if (!this.saveMeError) {
        this.closeManageAccount()
        this.user = this.connectedUser
      }
    },
    changeSwith() {
      this.user = {
        ...this.user,
        password: '',
        new_password: '',
        new_password_confirmation: ''
      }
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';
@media screen and (max-width: $--xs) {
  /*.dialog-account {*/
  /*  width: 100%;*/
  /*}*/
}
</style>
