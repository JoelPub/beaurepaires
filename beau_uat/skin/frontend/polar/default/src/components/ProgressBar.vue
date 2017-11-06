<template>
  <div v-bind:class="getBarClass()" role="progressbar" :aria-valuenow="progressPercent" aria-valuemin="0" v-bind:aria-valuetext="currentStepLabel()" aria-valuemax="100">
    <ul class="steps">
      <li v-for="(step, index) in steps" v-bind:class="getStepClass(index)" :style="stepsStyle">{{ step }}</li>
    </ul>
    <svg version="1.1" id="progressbar-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
    	 viewBox="0 0 236.6 22" style="enable-background:new 0 0 236.6 22;" xml:space="preserve" preserveAspectRatio="none">
      <polygon id="base" class="bar-base" points="224,21.5 0.4,21.5 0.4,0.6 224,0.6 236,11 "/>
      <polygon id="progress" class="bar-progress" v-bind:points="progressBarPoints()"/>
      <polygon id="border" class="bar-border" points="224,21.5 0.4,21.5 0.4,0.6 224,0.6 236,11 "/>
    </svg>
  </div>
</template>

<script>
// Progress bar used in cart

const data = {
  show: false,
  baseWidth: 224,
  progressPoints: [0, 12],
  progressPercent: 0,
  current: 1,
  steps: [],
  stepsStyle: 'width: 20%'
}

export default {
  name: 'progress-bar',
  data: () => {
    return data
  },
  props: ['configId'],
  beforeMount () {
    try {
      const jsonData = JSON.parse(document.getElementById(this.configId).innerHTML)
      this.show = jsonData.show || false
      this.current = jsonData.current || 1
      this.steps = jsonData.steps || []

      if (this.steps.length === 0) {
        this.show = false
      }
      if (this.current < 1) {
        this.current = 1
      } else if (this.current > this.steps.length) {
        this.current = this.steps.length
      }
      this.progressPercent = Math.round(this.current / this.steps.length * 100)
      this.stepsStyle = 'width: ' + (100 / this.steps.length) + '%'
    } catch (e) {
      console.error('JSON error in progress bar', e)
    }
  },
  methods: {
    progressBarPoints () {
      return this.calcPoint(0) + ',21.5 0.4,21.5 0.4,0.6 ' + this.calcPoint(0) + ',0.6 ' + this.calcPoint(1) + ',11'
    },
    calcPoint (index) {
      let barOffset = (this.baseWidth / 100) * (100 - this.progressPercent)
      return ((this.baseWidth + this.progressPoints[index]) - barOffset)
    },
    currentStepLabel () {
      return 'Step ' + this.current + ': ' + this.steps[(this.current - 1)]
    },
    getBarClass () {
      let classes = ['progress-bar']
      if (this.show) {
        classes.push('show')
      }
      return classes.join(' ')
    },
    getStepClass (index) {
      let classes = ['step']
      let currentIndex = this.current - 1

      if (index === currentIndex) {
        classes.push('active')
      } else if (index < currentIndex) {
        classes.push('past')
      }

      return classes.join(' ')
    }
  }
}
</script>
