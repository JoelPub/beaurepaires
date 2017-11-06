<template>
  <div v-bind:class="headerModeClasses">
    <div class="top-banner-wrapper">
      <div class="row full-width">
        <div class="small-6 medium-6 columns">
          <div class="header-hamburger show-for-small-only">
            <div class="toggle-topbar" aria-haspopup="true" v-on:click="toggleNav()">
              <i class="fa fa-bars" aria-hidden="true"></i>
            </div>
          </div>
          <h1 class="title">
            <a href="/"><img src="../../images/logos/logo_title.svg" :alt="headerTitle" class="logo-large hide-for-ie"><img src="../../images/logos/logo_title_mobile.svg" :alt="headerTitle" class="logo-small"><img src="../../images/logos/logo_title_fallback.png" :alt="headerTitle" class="logo-large show-for-ie-only"></a>
          </h1>
        </div>
        <div class="small-6 medium-6 columns text-right">
          <ul class="nav-actions">
            <li v-for="(actionIcon, index) in actionIcons" v-bind:class="[actionIcon.label, visibilityClass(actionIcon.visibility)]">
              <a
                v-if="actionIcon.link !== null"
                :href="actionIcon.link"
                class="icon-action"
                tabindex="0"
                :aria-label="actionIcon.label"
              ><i :class="['fa', actionIcon.icon]" aria-hidden="true" :title="actionIcon.label"></i><span class="icon-label" v-if="actionIcon.showLabel">{{ actionIcon.label }}</span></a>
              <span
                v-bind:class="isActive(actionIcon.isActive)"
                v-else
                tabindex="0"
                role="button"
                v-bind:aria-pressed="pressedType(actionIcon.isToggle, actionIcon.isActive)"
                v-bind:aria-expanded="isExpanded(actionIcon.isActive)"
                :aria-label="actionIcon.label"
                aria-haspopup="true"
                v-on:click="iconAction(index, actionIcon.action)"
                v-on:keyup.enter="iconAction(index, actionIcon.action)"
                v-on:keyup.space="iconAction(index, actionIcon.action)"
              ><i :class="['fa', actionIcon.icon]" aria-hidden="true" :title="actionIcon.label"></i><span class="icon-label" v-if="actionIcon.showLabel">{{ actionIcon.label }}</span>
              <span class="icon-badge" v-if="actionIcon.badge > 0">{{ actionIcon.badge }}</span>
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="header-overlays">
      <div v-bind:class="overlayClasses('search')">
        <SearchPanel
          :label="searchPanelText"
          :input-name="searchPanelInputName"
          :action="searchPanelAction"
          :search-api="searchApi"
          :search-view-all="searchViewAll"
          :not-found="searchPanelNoResult"
          :con-error="searchConnectionError"
          @close="closePanel"
        />
      </div>
      <div v-bind:class="overlayClasses('login')">
        <LoginPanel
          :login-text="loginLoginText"
          :login-icon="loginLoginIcon"
          :login-url="loginLoginUrl"
          :login-formkey="accountFormkey"
          :login-forgot="loginForgotPassword"
          :login-fb-url="loginSocialFbUrl"
          :login-fb-width="loginSocialFbPopupWidth"
          :login-fb-height="loginSocialFbPopupHeight"
          :login-google-url="loginSocialGoogleUrl"
          :login-google-width="loginSocialGooglePopupWidth"
          :login-google-height="loginSocialGooglePopupHeight"
          :signup-text="loginSignupText"
          :signup-icon="loginSignupIcon"
          :signup-url="loginSignupUrl"
          @close="closePanel"
        />
      </div>
      <div v-bind:class="overlayClasses('account')">
        <AccountPanel
          :my-account-text="accountMyAccountText"
          :my-account-icon="accountMyAccountIcon"
          :my-account-url="accountMyAccountUrl"
          :logout-text="accountLogoutText"
          :logout-icon="accountLogoutIcon"
          :logout-url="accountLogoutUrl"
          @close="closePanel"
        />
      </div>
      <div v-bind:class="overlayClasses('minicart')">
        <MiniCart 
          :cart-display-url="minicartDisplayUrl"
          :cart-update-url="minicartUpdateUrl"
          :cart-delete-url="minicartDeleteUrl"
          :cart-checkout-url="minicartCheckoutUrl"
          :cart-landing-url="minicartCartUrl"
          :cart-store-url="minicartChangeStoreUrl"
          :badge-cart-qty="badgeQty"
          @close="closePanel"
          @update="updateBadgeCnt"
        />
      </div>
    </div>
  </div>
</template>

<script>
import $ from 'jquery'
import SearchPanel from './sub/SearchPanel'
import LoginPanel from './sub/LoginPanel'
import AccountPanel from './sub/AccountPanel'
import MiniCart from './sub/MiniCart'
import 'babel-polyfill'

const data = {
  actionIcons: [],
  activeOverlay: null,
  badgeQty: '',
  headerModeClasses: {
    'header-container': true,
    'header-mode-open': false
  }
}

export default {
  name: 'main-header',

  data: () => {
    return data
  },
  props: [
    'headerTitle',
    'headerIconsConfigId',
    'searchPanelText',
    'searchPanelInputName',
    'searchPanelAction',
    'searchPanelNoResult',
    'searchConnectionError',
    'searchApi',
    'searchViewAll',
    'accountMyAccountText',
    'accountMyAccountIcon',
    'accountMyAccountUrl',
    'accountLogoutText',
    'accountLogoutIcon',
    'accountLogoutUrl',
    'accountFormkey',
    'loginLoginText',
    'loginLoginIcon',
    'loginLoginUrl',
    'loginSignupText',
    'loginSignupIcon',
    'loginSignupUrl',
    'loginForgotPassword',
    'loginSocialFbUrl',
    'loginSocialFbPopupWidth',
    'loginSocialFbPopupHeight',
    'loginSocialGoogleUrl',
    'loginSocialGooglePopupWidth',
    'loginSocialGooglePopupHeight',
    'minicartDisplayUrl',
    'minicartUpdateUrl',
    'minicartDeleteUrl',
    'minicartCheckoutUrl',
    'minicartCartUrl',
    'minicartChangeStoreUrl'
  ],
  watch: {
  },
  components: { SearchPanel, LoginPanel, AccountPanel, MiniCart },

  beforeMount () {
    // load config settings from inline JSON
    data.actionIcons = JSON.parse(document.getElementById(this.headerIconsConfigId).innerHTML)

    // expose incrementBadge to the global
    window.incrementBadge = (label, cartQty) => { this.incrementBadge(label, cartQty) }
  },
  mounted () {
    this.mobileNavClick()
    this.badgeItemsQty()
  },
  methods: {
    pressedType: (isToggle, isActive) => {
      if (isToggle) {
        return (isActive) ? 'true' : 'false'
      } else {
        return ''
      }
    },

    visibilityClass: (visibility) => {
      let visibilityClass = ''

      switch (visibility) {
        case ('desktop'):
          visibilityClass = 'hide-for-small-only'
          break

        case ('mobile'):
          visibilityClass = 'show-for-small-only'
          break

        case ('all'):
        default:
          // leave as ''
      }

      return visibilityClass
    },

    overlayClasses: (name) => {
      let classes = ['header-overlay', 'header-' + name]
      if (name === data.activeOverlay) {
        classes.push('active')
      }
      return classes
    },

    isActive: (isActive) => {
      return (isActive) ? ['icon-action', 'active'] : 'icon-action'
    },

    isExpanded: (isExpanded) => {
      return (isExpanded) ? 'true' : 'false'
    },

    iconAction: (index, action) => {
      const toggleOverlay = (index, name) => {
        let actionIcons = data.actionIcons

        data.headerModeClasses['header-mode-open'] = false

        for (let i = 0; i < actionIcons.length; i++) {
          actionIcons[i].isActive = false
        }

        if (data.activeOverlay === name) {
          data.activeOverlay = null
        } else {
          data.activeOverlay = name
          actionIcons[index].isActive = true
          data.headerModeClasses['header-mode-open'] = true
        }

        data.actionIcons = actionIcons
      }

      switch (action) {
        case ('toggleSearch'):
          toggleOverlay(index, 'search')
          $('nav.top-bar').removeClass('expanded')
          $('body').removeClass('overlay-expanded')
          break

        case ('toggleLogin'):
          toggleOverlay(index, 'login')
          $('nav.top-bar').removeClass('expanded')
          $('body').removeClass('overlay-expanded')
          break

        case ('toggleAccount'):
          toggleOverlay(index, 'account')
          $('nav.top-bar').removeClass('expanded')
          $('body').removeClass('overlay-expanded')
          break

        case ('toggleCart'):
          toggleOverlay(index, 'minicart')
          $('nav.top-bar').removeClass('expanded')
          $('body').removeClass('overlay-expanded')
          break

        default:
          // do nothing
      }
    },

    toggleNav: () => {
      window.Foundation.libs.topbar.toggle()
      $('body').toggleClass('overlay-expanded')
    },

    closePanel: () => {
      let actionIcons = data.actionIcons

      for (let i = 0; i < actionIcons.length; i++) {
        actionIcons[i].isActive = false
      }

      data.actionIcons = actionIcons
      data.activeOverlay = null
      data.headerModeClasses['header-mode-open'] = false
    },
    badgeItemsQty () {
      for (let actionIcon of data.actionIcons) {
        if (actionIcon.label === 'Cart') {
          this.badgeQty = actionIcon.badge
          this.addCBadge(this.badgeQty)
        }
      }
    },
    incrementBadge: (iconLabel, cartQty) => {
      for (let actionIcon of data.actionIcons) {
        if (actionIcon.label === iconLabel) {
          actionIcon.badge = cartQty
          data.badgeQty = cartQty
        }
      }
    },
    addCBadge (badge) {
      if (badge > 0) {
        $('.nav-actions').find('li.Cart').addClass('isCartItem')
      }
    },
    updateBadgeCnt (badgeCnt) {
      this.incrementBadge('Cart', badgeCnt)
    },
    mobileNavClick () {
      const $topBar = $('.top-bar')
      const showSearch = () => { this.iconAction(0, 'toggleSearch') }
      const showLogin = () => { this.iconAction(1, 'toggleLogin') }
      $topBar.find('.m-search').on('click', showSearch)
      $topBar.find('.m-login').on('click', showLogin)
    }
  }
}
</script>
