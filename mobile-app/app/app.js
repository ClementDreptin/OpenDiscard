import Vue from "nativescript-vue";
import store from "./store";
import axios from "axios";
import App from "./views/App";

global.axios = axios.create({
    baseURL: 'http://51.68.213.80:19180'
});

new Vue({
    store,
    render: h => h(App)
}).$start();
