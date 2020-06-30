<template>
    <Page>
        <FlexboxLayout>
            <StackLayout class="form">
                <Image class="logo" src="res://icon" />
                <Label class="header" text="OpenDiscard"/>

                <StackLayout class="input-field" marginBottom="25">
                    <TextField class="input" hint="Email" keyboardType="email" autocorrect="false" autocapitalizationType="none" v-model="email" returnKeyType="next" fontSize="18" />
                    <StackLayout class="hr-light" />
                </StackLayout>

                <StackLayout class="input-field" marginBottom="25">
                    <TextField ref="password" class="input" hint="Password" secure="true" v-model="password" fontSize="18" />
                    <StackLayout class="hr-light" />
                </StackLayout>

                <Button text="Sign In" @tap="signIn" class="btn btn-primary m-t-20" />
            </StackLayout>

            <Label class="login-label sign-up-label" @tap="goToSignUp">
                <FormattedString>
                    <Span text="Don’t have an account? Sign Up!" />
                </FormattedString>
            </Label>
        </FlexboxLayout>
    </Page>
</template>

<script>
    import Home from "~/views/Home";

    export default {
        name: "SignIn",
        data() {
            return {
                email: "caden@test.com",
                password: "1234",
                fail: null
            }
        },
        methods: {
            signIn() {
                global.axios.post('/users/signin/', {}, {
                    auth: {
                        username: this.email,
                        password: this.password
                    }
                }).then(response => {
                    this.fail = false;
                    this.$store.state.user = response.data.user;

                    this.$navigateTo(Home).catch(err => console.log(err));
                }).catch(err => console.log(err));
            },

            goToSignUp() {

            }
        },
        mounted() {
            if (this.$store.state.user) {
                this.$navigateTo(Home).catch(err => console.log(err));
            }
        }
    }
</script>

<style scoped lang="scss">
    .page {
        align-items: center;
        flex-direction: column;
    }
    .form {
        margin-left: 30;
        margin-right: 30;
        flex-grow: 2;
        vertical-align: middle;
    }

    .logo {
        margin-bottom: 12;
        height: 90;
        font-weight: bold;
    }
    .header {
        horizontal-align: center;
        font-size: 25;
        font-weight: 600;
        margin-bottom: 50;
        text-align: center;
        color: #53ba82;
    }

    .input-field {
        margin-bottom: 25;
    }
    .input {
        font-size: 18;
        placeholder-color: #A8A8A8;
    }
    .input-field .input {
        font-size: 54;
    }

    .btn-primary {
        height: 50;
        margin: 30 5 15 5;
        background-color: #D51A1A;
        border-radius: 5;
        font-size: 20;
        font-weight: 600;
    }
    .login-label {
        horizontal-align: center;
        color: #A8A8A8;
        font-size: 16;
    }
    .sign-up-label {
        margin-bottom: 20;
    }
    .bold {
        color: #000000;
    }
</style>
