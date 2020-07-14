import Vue from "nativescript-vue";
import store from "./store";
import axios from "axios";
import App from "./views/App";
import {decode, encode} from 'base-64';
const WebSocket = require("nativescript-websockets");

// Base 64
if (!global.btoa) {
    global.btoa = encode;
}

if (!global.atob) {
    global.atob = decode;
}

// Global instance of axios
global.axios = axios.create({
    baseURL: 'https://opendiscard.com:19143'
});

// Global bus for events
global.bus = new Vue({});

global.socket = new WebSocket('wss://opendiscard.com:19300/ws', []);
global.socket.open();

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
