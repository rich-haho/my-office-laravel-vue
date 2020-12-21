<template>
  <el-popover
    v-model="showPopover"
    placement="bottom"
    width="300"
    trigger="click"
  >
    <div class="user-popover" @click="togglePopover">
      <el-button type="primary" class="mt-10" @click="showMyBookings">
        {{ $t('bookings.my_bookings') }}
      </el-button>
      <br />
      <el-button type="warning" class="mt-10" @click="showManageAccount">
        {{ $t('auths.my_account') }}
      </el-button>
      <br />
      <el-button
        class="mt-10"
        type="danger"
        :loading="logoutLoading"
        @click="logout"
      >
        {{ $t('auths.logout') }}
      </el-button>
    </div>
    <div slot="reference" class="user-container all-align">
      <div class="avatar-container">
        <i class="el-icon-user-solid"></i>
        <span class="hidden-md-and-up pl-5">
          {{ $t('auths.my_account').toUpperCase() }}
        </span>
      </div>
      <div class="drop-button">
        <i class="el-icon-arrow-down"></i>
      </div>
    </div>
  </el-popover>
</template>

<script>
import { mapState, mapActions, mapGetters } from 'vuex'
import router from '@/router'

export default {
  name: 'UserPopover',
  data() {
    return {
      showPopover: false
    }
  },

  computed: {
    ...mapState('auth', ['logoutLoading']),
    ...mapGetters('auth', ['connectedUser'])
  },
  mounted: function() {},
  methods: {
    ...mapActions('auth', ['logout', 'showManageAccount']),
    togglePopover() {
      this.showPopover = false
    },
    showMyBookings() {
      const path = `/bookings`
      if (this.$route.path !== path) {
        return router.push({ name: 'bookings' })
      }
    }
  }
}
</script>

<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';

.user-container {
  cursor: pointer;
  .avatar-container {
    z-index: 2;
    @media screen and (max-width: $--md) {
      width: 100%;
    }
    .account-container {
      @media screen and (max-width: $--md) {
        width: 100%;
      }
    }

    i {
      font-size: 25px;
    }
  }
  .drop-button {
    position: relative;
    height: 20px;
    width: 40px;
    padding-right: 2px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 5px;
    border-radius: 12px;
    i {
      width: 25px;
      font-size: 20px;
      color: $primary-color;
      line-height: 40px;
    }
  }
}
</style>
