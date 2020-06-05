import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../views/Home')
    },
    {
        path: '/signUp',
        name: 'signUp',
        component: () => import('../views/SignUp')
    },
    {
        path: '/signIn',
        name: 'signIn',
        component: () => import('../views/SignIn')
    }
]

const router = new VueRouter({
    routes
})

export default router
