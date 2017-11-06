<template>
  <div id="die" v-bind:class="setClass(result, rolling)">
    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 300 300" style="enable-background:new 0 0 300 300;" xml:space="preserve">
      <g id="base">
      	<path id="base_1_" class="st0" d="M263.8,293H37c-15.4,0-28-12.6-28-28V38.3c0-15.4,12.6-28,28-28h226.8c15.4,0,28,12.6,28,28V265
      		C291.8,280.4,279.2,293,263.8,293z"/>
      </g>
      <g id="dots">
      	<circle class="dot" id="one" cx="87" cy="66" r="29"/>
      	<circle class="dot" id="two" cx="87" cy="149.6" r="29"/>
      	<circle class="dot" id="three" cx="152.4" cy="151.6" r="29"/>
      	<circle class="dot" id="four" cx="87" cy="232.3" r="29"/>
      	<circle class="dot" id="five" cx="218" cy="66" r="29"/>
      	<circle class="dot" id="six" cx="218" cy="149.6" r="29"/>
      	<circle class="dot" id="seven" cx="218" cy="232.3" r="29"/>
      </g>
    </svg>
  </div>
</template>

<script>
export default {
  name: 'die',
  props: ['result', 'rolling'],
  methods: {
    setClass: (resultLabel, isRolling) => {
      return ['die', 'result-' + resultLabel, ((isRolling) ? 'rolling' : 'stop-rolling')]
    }
  }
}
</script>
