<template>
    <div id="signup">
        <h1 class="title is-1">Sign Up</h1>

        <div class="container" id="signup-form">
            <div class="field">
                <div class="control">
                    <input class="input is-primary is-rounded" type="text" v-model="username" placeholder="Username">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input class="input is-primary is-rounded" type="text" v-model="email" placeholder="E-mail address">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input class="input is-primary is-rounded" type="password" v-model="password" placeholder="Password">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input class="input is-primary is-rounded" type="password" v-model="passwordVerify" placeholder="Confirm password">
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link is-rounded is-light" @click="signUp">Sign Up</button>
                </div>

                <div class="control">
                    <span><router-link class="button is-info is-light is-rounded" to="signIn">Already have an account? Sign In!</router-link></span>
                </div>
            </div>
        </div>

        <!-- Error message if the user provides credentials that don't match with the API's requirements -->
        <div class="container" id="signup-message">
            <article class="message is-danger" v-show="fail">
                <div class="message-header">
                    <p>Error</p>
                </div>
                <div class="message-body">
                    {{ fail }}
                </div>
            </article>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SignUp",
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
                            this.$store.commit('signIn', response.data.user);
                            this.$router.push('/');
                        }).catch(err => {
                            this.fail = err.response.data.message
                    })
                } else {
                    this.fail = "Passwords are not identical.";
                }
            }
        }
    }
</script>

<style scoped>
    #signup {
        margin: auto;
        width: 80%;
    }

    #signup-form {
        width: 90%;
    }

    #signup-message {
        width: 90%;
        margin-top: 1em;
    }
</style>