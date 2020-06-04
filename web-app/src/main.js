import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import 'bulma/css/bulma.css'

window.axios = axios.create({
    baseURL: 'http://api.opendiscard.local:19180'
});

Vue.config.productionTip = false

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')
