<template>
  <div>
    <page-detail-bar
      :title="$t('home.title', { user: user.name })"
      :short-title="$t('home.short_title')"
      :description="$t('home.description')"
    />
    <div class="home global-padding ">
      <div class="search-container">
        <div class="bottom-box">
          <search-bar />
        </div>
      </div>
      <category-container />
      <product-container />
      <favorites-container />
    </div>
  </div>
</template>

<script>
import CategoryContainer from '@/views/HomePartials/CategoryContainer.vue'
import FavoritesContainer from '@/views/HomePartials/FavoritesContainer.vue'
import ProductContainer from '@/views/HomePartials/ProductContainer.vue'
import PageDetailBar from '@/components/PageDetailBar.vue'
import { mapState } from 'vuex'
import SearchBar from '@/views/Products/SearchBar'

export default {
  components: {
    SearchBar,
    CategoryContainer,
    FavoritesContainer,
    ProductContainer,
    PageDetailBar
  },
  computed: {
    ...mapState('auth', ['user'])
  }
}
</script>
