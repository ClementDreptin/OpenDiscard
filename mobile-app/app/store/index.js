import Vue from 'nativescript-vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        user: null,
        servers: [],
        currentServer: null,
        currentTextChannel: null
    },
    mutations: {

    }
});
