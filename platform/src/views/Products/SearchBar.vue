<template>
  <el-row :gutter="15">
    <el-col :md="12" :lg="14">
      <component
        :is="disableAutocomplete ? 'el-input' : 'el-autocomplete'"
        v-model="searchText"
        :fetch-suggestions="querySearch"
        class="search-bar"
        :placeholder="$t('home.search_placeholder')"
        prefix-icon="icon ion-ios-search"
        clearable
        :trigger-on-focus="false"
        @keyup.enter.native="resultList"
      >
        <template slot-scope="{ item }">
          <div class="value search-value" @click="handleSelect(item.id)">
            <el-image
              :src="item.assets ? item.assets.full_path : ''"
              class="search-image"
              fit="contain"
            />
            <span class="pl-20 search-text">
              {{ item.name }}
            </span>
          </div>
        </template>
      </component>
    </el-col>
    <el-col :md="6" :lg="5">
      <el-button
        class="search-button pt-20 pb-15"
        type="danger"
        @click="resultList"
      >
        {{ $t('home.search') }}
      </el-button>
    </el-col>
    <el-col :md="6" :lg="5">
      <el-button
        class="pt-20 pb-15"
        type="primary"
        plain
        @click="$router.push({ name: 'categories' })"
      >
        {{ $t('home.explore_category') }}
      </el-button>
    </el-col>
  </el-row>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  props: {
    defaultSearchText: {
      type: String,
      default: ''
    },
    disableAutocomplete: {
      type: Boolean,
      default: false
    }
  },
  data() {
    return {
      searchText: this.defaultSearchText
    }
  },
  computed: {
    ...mapState('products', ['searchProductList'])
  },
  methods: {
    ...mapActions('products', ['searchProducts']),
    async querySearch(queryString, cb) {
      await this.searchProducts({
        filters: queryString
      })
      let resultList = this.searchProductList.data.map(item => {
        return { ...item }
      })
      cb(resultList)
    },
    handleSelect(id) {
      this.$router.push({ name: 'product-index', params: { id: id } })
    },
    resultList() {
      if (this.disableAutocomplete) {
        this.searchProducts({
          filters: this.searchText,
          perPage: 50
        })
      } else {
        this.$router.push({
          name: 'product-search-results',
          params: { qs: this.searchText }
        })
      }
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';
.search-container {
  padding-bottom: 40px;
  .top-box {
    padding-left: 20px;
    display: flex;
    font-size: 13px;
    color: $color-light-dark;

    .image {
      height: 90px;
    }
    .text-container {
      padding: 10px 10px 10px 20px;
      .bottom-text {
        font-weight: bold;
      }
    }
  }

  .search-button {
    margin-bottom: 15px;
  }
}
</style>
<style lang="scss">
@import '~@/assets/styles/_variables.scss';
.search-container {
  .search-bar {
    width: 100%;
    margin-bottom: 15px;
    .el-input__inner {
      border-color: $primary-color;
      padding-top: 25px;
      padding-bottom: 25px;
    }
    input {
      padding-left: 42px;
      color: $primary-color;
    }
    .el-input__prefix {
      left: 12px;
    }
    .ion-ios-search {
      color: $primary-color;
      font-size: 20px;
    }
  }
}

.search-value {
  padding: 10px 0;
  .el-image.search-image {
    width: 50px;
    height: 50px;
    vertical-align: middle;
  }

  .search-text {
    font-size: 1rem;
  }
}
</style>
