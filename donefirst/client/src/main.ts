import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import axios from 'axios'

Vue.config.productionTip = false

// Change this baseURL for the desired development environment.
axios.defaults.baseURL = 'http://192.168.56.105:8081'

new Vue({
  router,
  store,
  vuetify,
  render: h => h(App)
}).$mount('#app')
