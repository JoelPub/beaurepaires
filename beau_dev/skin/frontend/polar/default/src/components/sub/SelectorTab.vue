<template>
  <li
    :id="tabId + '-tab'"
    :class="{'selector-tab': true, 'active': isActive, 'small': (tabType === 'small')}"
    ref="thisTab"
    role="tab"
    tabindex="0"
    :aria-controls="tabId + '-panel'"
    :aria-selected="isActive"
    v-on:click="onSelect(tabId)"
    v-on:keyup.enter="onSelect(tabId)"
    v-on:keyup.space="onSelect(tabId)"
  >
    <span class="title">
      <i v-if="tabIcon" :class="['fa', tabIcon]" aria-hidden="true"></i>
      <span class="show-for-medium-up">{{ tabTitle }}</span>
      <span class="show-for-small">{{ tabTitleShort }}</span>
    </span>
  </li>
</template>

<script>

export default {
  name: 'SelectorTab',

  props: [
    'tabId',
    'tabTitle',
    'tabTitleShort',
    'tabType',
    'isActive',
    'tabIcon',
    'onSelect'
  ]
}

</script>
