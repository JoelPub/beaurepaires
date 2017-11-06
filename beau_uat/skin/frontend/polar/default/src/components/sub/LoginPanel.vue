<template>
  <div class="container-login">
    <div class="overlay-header">
      <i aria-hidden="true" class="fa fa-user-o"></i>
      <span>Login / Register</span>
      <button class="close-panel" v-on:click="close">
        <i class="fa fa-times" aria-hidden="true"></i>
      </button>
    </div>
    <div class="overlay-body">
      <h3>Login</h3>
      <div>
          <form @submit.prevent="beforeSubmit" ref="form" :action="loginUrl" method="post">
            <div class="email-cont">
              <input name="login[username]" type="text" placeholder="Email Address" v-model="form.emailValue"  @input="$v.form.emailValue.$touch()">
              <div v-if="$v.form.emailValue.$error">
                <span class="error" v-if="!$v.form.emailValue.required">This is a required field - It must be a valid email address</span>
                <span class="error" v-if="!$v.form.emailValue.email">This is a required field - It must be a valid email address</span>
              </div>
            </div>
            <div class="password-cont">
              <input name="login[password]" type="password"placeholder="Password" v-model="form.password" @input="$v.form.password.$touch()">
              <input name="form_key" type="hidden" :value="loginFormkey" />
              <div v-if="$v.form.password.$error">
                <span class="error" v-if="!$v.form.password.required">Please enter your password</span>
              </div>
            </div>
            <div class="submit-cont">
              <button type="submit" class="flat-button regular secondary">{{loginText}}</button>
              <a :href="loginForgot" class="forgot">Forgot Password?</a>
            </div>
            <hr class="sep-label" data-content="OR">
            <h3>Login with your social account</h3>
            <div class="social-cont">
              <a class="fb" href="javascript:void(0);" :onclick="this.loginFbOnclick"  >
                <span class="social-ico"></span>
                <span class="social-label">Login with Facebook</span>
              </a>
              <a class="google" href="javascript:void(0);" :onclick="loginGoogleOnclick">
                <span class="social-ico"></span>
                <span class="social-label">Login with Google+</span>
              </a>
            </div>
            <div class="register-cont">
              <a class="flat-button small white" :href="signupUrl" >{{signupText}}</a>
              <!-- <pre>validationGroup: {{ $v.form }}</pre> -->
            </div>
            
          </form>
      </div>
    </div>

    <!-- <div class="row">
      <div class="columns small-12">
        <a :href="loginUrl" class="overlay-links"><i :class="['fa', 'fa-fw', loginIcon]" aria-hidden="true"></i> {{ loginText }}</a>
      </div>
    </div>
    <div class="row">
      <div class="columns small-12">
        <a :href="signupUrl" class="overlay-links"><i :class="['fa', 'fa-fw', signupIcon]" aria-hidden="true"></i> {{ signupText }}</a>
      </div>
    </div> -->
  </div>
</template>

<script>
import Vue from 'vue'
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

const { required, email } = require('vuelidate/lib/validators')

const data = {
  form: {
    emailValue: '',
    password: ''
  },
  loginFbOnclick: '',
  loginGoogleOnclick: ''
}

export default {
  name: 'LoginPanel',
  props: [
    'loginText',
    'loginIcon',
    'loginUrl',
    'loginFormkey',
    'loginForgot',
    'loginFbUrl',
    'loginFbWidth',
    'loginFbHeight',
    'loginGoogleUrl',
    'loginGoogleWidth',
    'loginGoogleHeight',
    'signupText',
    'signupIcon',
    'signupUrl'
  ],
  mounted () {
    // console.log('mounted')
    this.loginFbOnclick = 'psLogin("' + this.loginFbUrl + '","' + this.loginFbWidth + '","' + this.loginFbHeight + '")'
    this.loginGoogleOnclick = 'psLogin("' + this.loginGoogleUrl + '",' + this.loginGoogleWidth + ',' + this.loginGoogleHeight + ')'
  },
  data: () => {
    return data
  },
  validations: {
    form: {
      emailValue: {
        required,
        email
      },
      password: {
        required
      }
    }
  },
  methods: {
    close: function () {
      this.$emit('close')
    },
    beforeSubmit () {
      this.$v.form.$touch()
      if (this.$v.form.$error) {
        // console.log(this.$v.form.$error)
      } else {
        // console.log(this.$v.form.$error)
        this.$refs.form.submit()
      }
    }
  }
}
</script>
