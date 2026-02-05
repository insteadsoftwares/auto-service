import Vue from 'vue'
import { ToastPlugin, ModalPlugin } from 'bootstrap-vue'
import VueCompositionAPI from '@vue/composition-api'

import router from './router'
import store from './store'
import App from './App.vue'

// Global Components
import './global-components'

// 3rd party plugins
import '@/libs/portal-vue'
import '@/libs/toastification'

// BSV Plugin Registration
Vue.use(ToastPlugin)
Vue.use(ModalPlugin)

// Composition API
Vue.use(VueCompositionAPI)

// import core styles
// require('@core/scss/core.scss')

// import assets styles
// require('@/assets/scss/style.scss')

Vue.config.productionTip = false


//Validate fr
import { extend, localize } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import fr from 'vee-validate/dist/locale/fr.json';
Object.keys(rules).forEach(rule => {
  extend(rule, rules[rule]);
});
localize('fr', fr);

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app')
