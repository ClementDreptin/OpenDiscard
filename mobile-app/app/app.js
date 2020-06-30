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

new Vue({
    store,
    render: h => h('frame', [h(App)])
}).$start();
