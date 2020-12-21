<template>
  <div class="aside-wrapper">
    <el-menu :router="true" :default-active="$route.path.split('/')[0]">
      <template v-for="(menu, key) in menus">
        <template>
          <template v-if="menu.hasOwnProperty('subs')">
            <el-submenu :key="key.toString()" :index="key.toString()">
              <template slot="title">
                <i class="icon" :class="menu.icon" />
                <span class="hidden-collapsed">{{ menu.title }}</span>
              </template>
              <template v-for="(sub, subKey) in menu.subs">
                <el-menu-item
                  :key="key.toString() + '-' + subKey.toString()"
                  :index="sub.route"
                >
                  <i class="icon" :class="menu.icon" />
                  <span class="hidden-collapsed">{{ sub.title }}</span>
                </el-menu-item>
              </template>
            </el-submenu>
          </template>
          <template v-else-if="can(menu.permission) || isSuperAdmin">
            <el-menu-item :key="key.toString()" :index="menu.route">
              <i class="icon" :class="menu.icon" />
              <span class="hidden-collapsed">{{ menu.title }}</span>
            </el-menu-item>
          </template>
        </template>
      </template>
    </el-menu>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'

export default {
  data() {
    return {
      menus: [
        {
          title: 'Accueil',
          icon: 'el-icon-s-home',
          route: '/zen-admin',
          permission: 'super admin only'
        },
        {
          title: 'Services',
          icon: 'el-icon-box',
          route: '/zen-admin/services',
          permission: 'manage services'
        },
        {
          title: 'Produits',
          icon: 'el-icon-shopping-cart-2',
          route: '/zen-admin/products',
          permission: ['manage my products only', 'manage products']
        },
        {
          title: 'Partenaires',
          icon: 'el-icon-truck',
          route: '/zen-admin/partners',
          permission: 'manage partners'
        },
        {
          title: 'Clients',
          icon: 'el-icon-s-custom',
          route: '/zen-admin/clients',
          permission: 'manage clients'
        },
        {
          title: 'Sites',
          icon: 'el-icon-location',
          route: '/zen-admin/sites',
          permission: 'manage sites'
        },
        {
          title: 'Réservations',
          icon: 'el-icon-notebook-2',
          route: '/zen-admin/bookings',
          permission: ['manage my bookings only', 'manage bookings']
        },
        {
          title: 'Utilisateurs',
          icon: 'el-icon-user-solid',
          route: '/zen-admin/users',
          permission: 'super admin only'
        },
        {
          title: 'Administrateurs',
          icon: 'el-icon-user',
          route: '/zen-admin/admins',
          permission: 'super admin only'
        }
      ]
    }
  },
  computed: {
    ...mapGetters('auth', ['can', 'isSuperAdmin'])
  },
  methods: {
    ...mapActions('common', ['toggleAside'])
  }
}
</script>

<style scoped lang="scss">
.el-menu {
  .icon {
    font-size: 1.2rem;
    margin-right: 15px;

    &:before {
      min-width: 25px;
      text-align: center;
    }
  }
}
</style>
