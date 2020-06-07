import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        user: null,
        servers: [],
        currentServer: null,
        currentTextChannel: null
    },
    mutations: {
    },
    actions: {
    },
    modules: {
    }
})
