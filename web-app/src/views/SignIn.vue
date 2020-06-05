<template>
    <div id="signin">
        <h1 class="title is-1">Sign In</h1>

        <div class="container" id="signin-form">
            <div class="field">
                <div class="control">
                    <input class="input is-primary is-rounded" type="text" v-on:keyup.enter="signIn" v-model="email" placeholder="E-mail">
                </div>
            </div>

            <div class="field">
                <div class="control">
                    <input class="input is-primary is-rounded" type="password" v-on:keyup.enter="signIn" v-model="password" placeholder="Password">
                </div>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link is-rounded is-light" @click="signIn">Sign In</button>
                </div>
                <div class="control">
                    <span><router-link class="button is-info is-light is-rounded" to="signUp">New to OpenDiscard ? Create an account!</router-link></span>
                </div>
            </div>
        </div>

        <!-- Error message that appears when the user provides an incorrect e-mail address or password -->
        <div class="container" id="signin-message">
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
        name: "SignIn",
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
                        let user = response.data.user;
                        this.$store.state.user = user;

                        if (user.avatar_url) {
                            axios.get(user.avatar_url)
                                .then(response => {
                                    this.$store.state.user.avatar = response.data;
                                    this.$router.push('/');
                                })
                                .catch(err => console.log(err.response.data.message));
                        } else {
                            this.$router.push('/');
                        }
                    }).catch(exception => {
                        this.fail = exception.response.data.message;
                });
            }
        }
    }
</script>

<style scoped>
    #signin {
        margin: auto;
        width: 80%;
    }

    #signin-form {
        width: 90%;
    }

    #signin-message {
        width: 90%;
        margin-top: 1em;
    }
</style>