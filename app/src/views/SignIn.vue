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
                    <span><router-link class="button is-link is-rounded" to="signUp">Create an account!</router-link></span>
                </div>
            </div>

            <div v-if="!isElectron" class="download-section">
                <div class="download-title">Download</div>
                <div class="download-options field is-grouped">
                    <div id="windows-button" class="control">
                        <a download :href="`${axios.defaults.baseURL}/download?platform=win&format=msi`" class="icons">
                            <i class="fab fa-windows"></i>
                        </a>
                    </div>
                    <div class="dropdown is-active">
                        <div class="dropdown-trigger">
                            <a class="icons" @click="dropdownActive = !dropdownActive">
                                <i class="fab fa-linux"></i>
                            </a>
                        </div>
                        <div v-if="dropdownActive" class="dropdown-menu">
                            <div class="dropdown-content">
                                <a class="dropdown-item" :href="`${axios.defaults.baseURL}/download?platform=linux&format=deb`">
                                    deb
                                </a>
                                <a class="dropdown-item" :href="`${axios.defaults.baseURL}/download?platform=linux&format=tar.gz`">
                                    tar.gz
                                </a>
                            </div>
                        </div>
                    </div>
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
                fail: false,
                axios: axios,
                isElectron: process.env.IS_ELECTRON,
                dropdownActive: false
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
        padding-top: 0.7em;
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

    .icons {
        color: #dcddde;
        font-size: 1.8em;
    }

    .download-section {
        margin-top: 10%;
    }

    .download-options {
        justify-content: center;
    }

    .download-title {
        color: #dcddde;
        text-align: center;
        margin-bottom: 1em;
    }

    #windows-button {
        margin-right: 1.5em;
    }
</style>