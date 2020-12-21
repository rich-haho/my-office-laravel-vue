<template>
  <div class="contact">
    <span class="contact--title">{{ $t('components.contact_title') }}</span>
    <span class="contact--hours">{{ userSite.open_time }}</span>
    <span class="contact--phone">{{ userSite.phone_number }}</span>
    <img src="~@/assets/images/anna.png" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  name: 'Contact',
  computed: {
    ...mapGetters('sites', ['userSite'])
  }
}
</script>

<style scoped lang="scss">
@import '~@/assets/styles/_variables.scss';

.contact {
  background-color: $color-white;
  position: relative;
  display: flex;
  flex-direction: column;
  width: 100%;
  border: 1px solid #c1c1c1;
  border-radius: 4px;
  padding: 15px 40px 15px 15px;
  .contact--title {
    color: $primary-color;
    font-weight: bold;
  }
  .contact--phone {
    font-size: 17px;
    font-weight: 100;
    margin: 5px 0;
  }
  img {
    position: absolute;
    transform: scaleX(-1);
    bottom: 0;
    right: -15px;
    height: 95%;
  }
}
</style>
