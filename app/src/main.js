import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import 'bulma/css/bulma.css'

window.axios = axios.create({
    baseURL: process.env.NODE_ENV !== 'production' ? 'http://api.opendiscard.local:19180' : 'http://51.68.213.80:19180'
});

document.addEventListener('contextmenu', event => {
    if (event.target.tagName.toLowerCase() !== "input") {
        event.preventDefault();
    }
});

Vue.config.productionTip = false;

// Bus for global events
Vue.prototype.$bus = new Vue();

Vue.prototype.$socket = new WebSocket(
    process.env.NODE_ENV !== 'production' ? 'ws://localhost:19300' : 'ws://51.68.213.80:19300'
);

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
