<template>
  <div>
    <div class="row">
      <div class="columns small-12">
        <a :href="loginUrl" class="overlay-links"><i :class="['fa', 'fa-fw', loginIcon]" aria-hidden="true"></i> {{ loginText }}</a>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <a :href="signupUrl" class="overlay-links"><i :class="['fa', 'fa-fw', signupIcon]" aria-hidden="true"></i> {{ signupText }}</a>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'LoginPanel',
  props: [
    'loginText',
    'loginIcon',
    'loginUrl',
    'signupText',
    'signupIcon',
    'signupUrl'
  ]
}
</script>
