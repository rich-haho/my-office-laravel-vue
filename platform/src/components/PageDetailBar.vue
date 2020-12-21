<template>
  <div
    v-if="!categoryId"
    :class="{
      'page-detail-no-deploy': !isDeploy,
      'page-detail-deploy': isDeploy
    }"
    class="page-detail main-detail global-padding vertical-align"
  >
    <div class="girl-image">
      <img src="~@/assets/images/anna_stand.png" />
    </div>
    <el-row class="details" :gutter="40">
      <el-col :xs="24" :md="actionButtonText ? 18 : 24" class="textDeploy">
        <div class="title hidden-xs-only">{{ title }}</div>
        <div class="title hidden-sm-and-up" @click="textDeploy">
          {{ shortTitle }}
        </div>
        <div
          class="description"
          :class="{
            'description-no-deploy': !isDeploy,
            'description-deploy': isDeploy
          }"
          @click="textDeploy"
        >
          {{ description }}
        </div>
      </el-col>
      <el-col v-if="actionButtonText" :xs="24" :md="6" class="mt-30 mt-sm-10">
        <el-button type="danger" @click="actionButton">
          {{ actionButtonText }}
        </el-button>
      </el-col>
      <slot name="breadcrumb" />
    </el-row>
  </div>
  <div v-else>
    <div
      class="page-detail category category-padding vertical-align"
      :class="{
        'page-detail-no-deploy': !isDeploy,
        'page-detail-deploy': isDeploy
      }"
    >
      <div class="category-image hidden-sm-and-down">
        <img :src="imageUrl" />
      </div>
      <div class="details pl-md-25 pl-75 pr-md-25 pr-0">
        <div class="text">
          <div class="title category" @click="textDeploy">{{ title }}</div>
          <div
            class="description"
            :class="{
              'description-no-deploy': !isDeploy,
              'description-deploy': isDeploy
            }"
            @click="textDeploy"
          >
            {{ description }}
          </div>
          <div class="favorite">
            <favorite
              :show-service="true"
              :value="favorite"
              @click.native="addServiceFavorite(categoryId)"
            />
          </div>
        </div>
      </div>
    </div>
    <div
      class="breadcrumb-container category-padding vertical-align hidden-sm-and-down"
    >
      <div class="empty"></div>
      <div class="pl-75">
        <slot name="breadcrumb" />
      </div>
    </div>
  </div>
</template>
<script>
import Favorite from './Favorite.vue'
export default {
  components: {
    Favorite
  },
  props: {
    title: {
      required: true,
      type: [Number, String, Array]
    },
    shortTitle: {
      required: false,
      type: [Number, String, Array],
      default: () => ''
    },
    description: {
      required: true,
      type: String
    },
    categoryId: {
      required: false,
      type: [String, Number],
      default: () => ''
    },
    imageUrl: {
      required: false,
      type: String,
      default: () => ''
    },
    favorite: {
      required: false,
      type: Boolean,
      default: () => false
    },
    addServiceFavorite: {
      type: Function,
      required: false,
      default: value => value
    },
    actionButton: {
      type: Function,
      required: false,
      default: () => {}
    },
    actionButtonText: {
      type: String,
      required: false,
      default: ''
    }
  },
  data() {
    return {
      isDeploy: false
    }
  },
  methods: {
    textDeploy() {
      this.isDeploy = !this.isDeploy
    }
  }
}
</script>
<style lang="scss" scoped>
@import '~@/assets/styles/_variables.scss';

.page-detail {
  .description {
    white-space: pre-line;
  }
  @media screen and (max-width: $--sm) {
    &.page-detail-no-deploy {
      height: 150px;
    }
    &.page-detail-deploy {
      height: auto;
    }
    &.category {
      height: auto;
    }
  }
  @media screen and (min-width: $--sm + 1) {
    height: 175px;
    &.category {
      height: 150px;
    }
  }

  background-color: #fff;

  &.main-detail {
    @media screen and (max-width: $--sm) {
      margin-top: 25px;
    }
    margin-top: 50px;
  }
  .girl-image {
    @media screen and (max-width: $--sm) {
      padding-right: 10px;
    }
    padding-right: 30px;
    img {
      @media screen and (max-width: $--sm) {
        width: 50px;
      }
      position: relative;
      width: 65px;
      top: -30px;
    }
  }
  .category-image {
    padding-right: 30px;
    position: relative;
    img {
      position: absolute;
      left: -150px;
      height: 150px;
      top: -30px;
    }
  }
  .details {
    @media screen and (max-width: $--sm) {
      .text {
        padding-top: 10px;
        padding-bottom: 10px;
        height: auto;
      }
    }
    @media screen and (min-width: $--sm + 1) {
      .text {
        height: 90px;
      }
    }

    .title {
      color: $primary-color;
      font-size: 20px;
      padding-bottom: 5px;
      font-weight: bold;
      &.category {
        color: #000;
      }
    }

    @media screen and (max-width: $--sm) {
      .description-no-deploy {
        -webkit-line-clamp: 2;
        max-height: 50px;
        text-align: justify;
        color: $color-light-dark;
        /*padding-bottom: 15px;*/
        font-size: 14px;
        line-height: 22px;

        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
      .description-deploy {
        text-align: justify;
        color: $color-light-dark;
        font-size: 14px;
        line-height: 22px;
        max-height: auto;
      }
    }
    @media screen and (min-width: $--sm + 1) {
      .description {
        color: $color-light-dark;
        padding-bottom: 15px;
        font-size: 14px;
        line-height: 22px;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }
    }

    .favorite {
      position: absolute;
      right: 20px;
      width: 250px;
      display: flex;
      justify-content: flex-end;
    }

    @media screen and (max-width: $--sm) {
      .favorite {
        padding-top: 5px;
        width: auto;
        position: relative;
        right: 0;
        margin-top: 10px;
      }
    }
    @media screen and (max-width: $--xs) {
      .favorite {
        margin-top: 0px;
      }
    }
  }
}

@media screen and (max-width: $--md) {
  .page-detail {
    padding: 10px !important;
  }
}

.breadcrumb-container {
  .empty {
    padding-right: 30px;
  }
  height: 54px;
  display: flex;
  align-items: center;
}
</style>
