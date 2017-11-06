<template>
  <div>
    <i class="fa fa-play"></i>
    <ul class="promo-messages promo-bar-msgs">
      <li v-for="(msgObj, key) in messages" v-if="msgObj.message">
        <a v-if="msgObj.link" :href="msgObj.link">{{ msgObj.message }}</a>
        <span v-else>{{ msgObj.message }}</span>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'promo-message',
  props: ['messages']
}
</script>
