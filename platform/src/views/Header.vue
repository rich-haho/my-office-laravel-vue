<template>
  <div class="header">
    <el-row type="flex">
      <el-col :xs="3" :sm="2" align="middle" class="hidden-md-and-up">
        <i class="el-icon-back back-button" @click="$router.back()"></i>
      </el-col>
      <el-col class="logo-main" :xs="18" :sm="20" :md="5" justify="center">
        <div
          class="logo-container vertical-align"
          @click="$router.push({ name: 'home' })"
        >
          <img class="logo" src="~@/assets/images/logo.png" />
        </div>
      </el-col>
      <el-col :xs="3" :sm="2" class="hidden-md-and-up">
        <div class="hamburger-container" @click="toggleMenu">
          <div id="hamburger-1" :class="['hamburger', { active: showMenu }]">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line last"></span>
          </div>
        </div>
      </el-col>
      <el-col :md="10" class="hidden-sm-and-down">
        <div v-if="!showSearch" class="tagline vertical-align">
          {{ $t('home.welcome') }}
        </div>
      </el-col>
      <el-col :md="3" class="hidden-sm-and-down">
        <locale-switcher />
      </el-col>
      <el-col :md="4" class="hidden-sm-and-down">
        <site-switcher />
      </el-col>
      <el-col :md="2" class="hidden-sm-and-down">
        <user-popover />
      </el-col>
    </el-row>
    <div :class="['menu-dropdown hidden-md-and-up', { open: showMenu }]">
      <div class="menu-item">
        <locale-switcher />
      </div>
      <hr class="divider" />
      <div class="menu-item">
        <site-switcher />
      </div>
      <hr class="divider" />
      <div class="menu-item user-detail">
        <user-popover />
      </div>
    </div>
    <user-account />
  </div>
</template>

<script>
import LocaleSwitcher from '@/views/Locale/Switcher.vue'
import SiteSwitcher from '@/views/Sites/Switcher.vue'
import UserPopover from '@/views/Auth/Popover'
import UserAccount from '@/views/Auth/Account'
import { mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'

export default {
  name: 'Header',
  components: { UserPopover, SiteSwitcher, LocaleSwitcher, UserAccount },
  data() {
    return {
      searchText: '',
      showSearch: false,
      modalVisisble: true
    }
  },
  computed: {
    ...mapState('auth', ['user']),
    ...mapState('products', ['productlist']),
    ...mapState('common', ['showMenu'])
  },
  watch: {
    $route() {
      this.checkShowSearch()
    }
  },
  created() {
    this.checkShowSearch()
  },
  methods: {
    ...mapActions('common', ['setShowMenu']),
    toggleMenu() {
      this.setShowMenu(!this.showMenu)
    },
    checkShowSearch() {
      this.showSearch = false

      // if (this.$route.name === 'home') {
      //   this.showSearch = true
      // } else {
      //   this.showSearch = false
      // }
    },
    errorMessage
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';

header {
  height: $header-height;
  .el-col {
    padding: 5px;
    height: $header-height;
    display: flex;
    align-items: center;
    justify-content: center;
  }
}
.logo-container {
  height: 100%;
  padding: 0 15px;
  cursor: pointer;
}
.tagline {
  height: 100%;
  padding: 0 5px;
  color: $color-light-dark;
}
.back-button {
  font-size: 50px;
  color: $primary-color;
  margin: 0 auto;
}
.search-container {
  width: 100%;
  height: 100%;

  .search-box {
    border: 0;
    ::placeholder {
      color: #000;
      opacity: 0.5;
    }

    :-ms-input-placeholder {
      color: #000;
      opacity: 0.5;
    }

    ::-ms-input-placeholder {
      color: #000;
      opacity: 0.5;
    }
  }
  .search-button {
    font-size: 30px;
    padding: 10px 20px;
    color: #fff;
    height: 100%;
    background-color: $primary-color;
  }
}

.hamburger-container {
  .hamburger {
    .line {
      width: 50px;
      height: 3px;
      background-color: $primary-color;
      border-radius: 5px;
      display: block;
      margin: 8px auto;
      transition: all 0.3s ease-in-out;
      &.last {
        width: 32px;
        margin-left: 18px;
      }
    }
  }
  .hamburger:hover {
    cursor: pointer;
  }
  #hamburger-1.active .line:nth-child(2) {
    opacity: 0;
  }
  #hamburger-1.active .line:nth-child(1) {
    transform: translateY(0.7rem) rotate(45deg);
    width: 40px;
  }
  #hamburger-1.active .line:nth-child(3) {
    transform: translateY(-0.75rem) rotate(-51deg);
    width: 40px;
    margin-left: auto;
  }
}

.menu-dropdown {
  height: 0;
  display: none;
  position: absolute;
  z-index: 2;
  width: 100%;
  left: 0;
  background-color: #fff;
  transition: all 0.2s ease-in-out;
  &.open {
    display: flex;
    /*align-items: center;*/
    flex-flow: column;
    height: 240px;
  }
  .menu-item {
    padding: 20px;
    &.user-detail {
      display: flex;
      padding-left: 15px;
      padding-top: 27.5px;
      padding-bottom: 27.5px;
      font: 400 13.3333px Arial;
      color: #606266;
      font-weight: bold;
      font-size: 14px;
    }
  }
  .divider {
    margin: 0px auto;
    width: 90%;
    border: 0.5px solid $color-light-grey;
  }
}
</style>
<style lang="scss">
@import '~@/assets/styles/_variables.scss';

.search-container {
  .search-box {
    .el-input__inner {
      background: transparent;
      color: black;
      height: $header-height;
      font-size: 18px;
      border: 0;
      font-style: italic;
    }
  }
}
</style>
