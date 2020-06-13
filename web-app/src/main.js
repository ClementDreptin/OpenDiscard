import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import 'bulma/css/bulma.css'

window.axios = axios.create({
    baseURL: 'http://api.opendiscard.local:19180'
});

document.addEventListener('contextmenu', event => {
    if (event.target.tagName.toLowerCase() !== "input") {
        event.preventDefault();
    }
});

Vue.config.productionTip = false;

// Bus for global events
Vue.prototype.$bus = new Vue();

new Vue({
    router,
    store,
    render: h => h(App),
    beforeCreate() {
        // Adds the token in an Authorization header if it's available in the store
        window.axios.interceptors.request.use(config => {
            if (this.$store.state.user) {
                config.headers = { Authorization: "Bearer "+this.$store.state.user.token }
            }
            return config;
        }, error => {
            return console.log(error);
        });
    }
}).$mount('#app')
