// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import Vuetify from 'Vuetify'
import 'Vuetify/dist/Vuetify.min.css'
import '@mdi/font/css/materialdesignicons.css'

Vue.use(Vuetify)


Vue.config.productionTip = false
export default new Vuetify({ })

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  vuetify: new Vuetify(),
  components: { App },
  template: '<App/>'
})
