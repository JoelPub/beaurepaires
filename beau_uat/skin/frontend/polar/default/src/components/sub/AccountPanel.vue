<template>
  <div class="container-account">
    <div class="overlay-header">
      <i aria-hidden="true" class="fa fa-user"></i>
      <span>Account</span>
      <button class="close-panel" v-on:click="close">
        <i class="fa fa-times" aria-hidden="true"></i>
      </button>
    </div>
    <div class="overlay-body">
          <a :href="myAccountUrl" class="flat-button regular secondary">{{ myAccountText }}</a>
          <a :href="logoutUrl" class="flat-button regular white">{{ logoutText }}</a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AccountPanel',
  props: [
    'myAccountText',
    'myAccountIcon',
    'myAccountUrl',
    'logoutText',
    'logoutIcon',
    'logoutUrl'
  ],
  methods: {
    close: function () {
      this.$emit('close')
    }
  }
}
</script>
