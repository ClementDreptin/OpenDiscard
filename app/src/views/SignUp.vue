<template>
    <div id="signup">
        <h1 class="title is-1">Sign Up</h1>

        <div class="container" id="signup-form">
            <div class="field">
                <div class="control">
                    <input class="input is-rounded" type="text" v-model="username" placeholder="Username">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input class="input is-rounded" type="text" v-model="email" placeholder="E-mail address">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input class="input is-rounded" type="password" v-model="password" placeholder="Password">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input class="input is-rounded" type="password" v-model="passwordVerify" placeholder="Confirm password">
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link is-rounded" @click="signUp">Sign Up</button>
                </div>

                <div class="control">
                    <span><router-link class="button is-link is-rounded" to="signIn">Already have an account? Sign In!</router-link></span>
                </div>
            </div>
        </div>

        <ErrorBox id="signup-message"/>
    </div>
</template>

<script>
    import ErrorBox from "../components/General/ErrorBox";
    import errorHandler from "../modules/Errors";

    export default {
        name: "SignUp",
        components: {
            ErrorBox
        },
        data() {
            return {
                username: "",
                email: "",
                password: "",
                passwordVerify: "",
                avatar_url: "",
                fail: false
            }
        },
        methods: {
            signUp() {
                if (this.password === this.passwordVerify) {
                    let params = {
                        username: this.username,
                        email: this.email,
                        password: this.password
                    };

                    axios.post('/users/signup/', params)
                        .then(response => {
                            this.fail = false;
                            this.$store.state.user = response.data.user;
                            this.$router.push('/');
                        }).catch(err => errorHandler(err, this));
                } else {
                    this.fail = "Passwords are not identical.";
                }
            }
        }
    }
</script>

<style scoped>
    h1 {
        color: #dcddde;
    }

    #signup {
        background-color: #272727;
        padding-left: 10%;
        padding-right: 10%;
        height: 100vh;
    }

    #signup-form {
        width: 90%;
    }

    #signup-message {
        width: 90%;
        margin-top: 1em;
    }
</style>