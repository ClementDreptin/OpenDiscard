<template>
    <div id="signin">
        <h1 class="title is-1">Sign In</h1>

        <div class="container" id="signin-form">
            <div class="field">
                <div class="control">
                    <input class="input is-rounded" type="text" @keyup.enter="signIn" @keypress="fail = null" v-model="email" placeholder="E-mail">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input class="input is-rounded" type="password" @keyup.enter="signIn" @keypress="fail = null" v-model="password" placeholder="Password">
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link is-rounded" @click="signIn">Sign In</button>
                </div>
                <div class="control">
                    <span><router-link class="button is-link is-rounded" to="signUp">New to OpenDiscard? Create an account!</router-link></span>
                </div>
            </div>
        </div>

        <ErrorBox id="signin-message"/>
    </div>
</template>

<script>
    import Home from "./Home";
    import ErrorBox from "../components/General/ErrorBox";
    import errorHandler from "../modules/Errors";

    export default {
        name: "SignIn",
        components: {
            Home,
            ErrorBox
        },
        data() {
            return {
                email: "",
                password: "",
                fail: false
            }
        },
        methods: {
            signIn() {
                axios.post('/users/signin/', {}, {
                    auth: {
                        username: this.email,
                        password: this.password
                    }
                }).then(response => {
                    this.fail = false;
                    this.$store.state.user = response.data.user;

                    this.$router.push('/');
                }).catch(err => errorHandler(err, this));
            }
        }
    }
</script>

<style scoped>
    h1 {
        color: #dcddde;
    }

    #signin {
        background-color: #272727;
        padding-left: 10%;
        padding-right: 10%;
        height: 100vh;
    }

    #signin-form {
        width: 90%;
    }

    #signin-message {
        width: 90%;
        margin-top: 1em;
    }
</style>