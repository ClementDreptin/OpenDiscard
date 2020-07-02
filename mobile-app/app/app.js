import Vue from "nativescript-vue";
import store from "./store";
import axios from "axios";
import App from "./views/App";
import {decode, encode} from 'base-64';

// Base 64
if (!global.btoa) {
    global.btoa = encode;
}

if (!global.atob) {
    global.atob = decode;
}

// Global instance of axios
global.axios = axios.create({
    baseURL: 'http://51.68.213.80:19180'
});

// Global bus for events
global.bus = new Vue({});

new Vue({
    store,
    render: h => h('frame', [h(App)]),
    beforeCreate() {
        // Adds the token in an Authorization header if it's available in the store
        global.axios.interceptors.request.use(config => {
            if (this.$store.state.user) {
                config.headers = { Authorization: "Bearer "+this.$store.state.user.token }
            }
            return config;
        }, error => {
            return console.log(error);
        });
    }
}).$start();
