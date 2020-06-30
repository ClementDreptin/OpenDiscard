<template>
    <Page actionBarHidden="true">
        <FlexboxLayout class="sign-form">
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

                <Button text="Sign In" @tap="signIn" class="btn" />
            </StackLayout>

            <Label class="switch-page-label" @tap="goToSignUp">
                <FormattedString>
                    <Span text="Don’t have an account? Sign Up!" />
                </FormattedString>
            </Label>
        </FlexboxLayout>
    </Page>
</template>

<script>
    import Home from "~/views/Home";
    import SignUp from "~/views/SignUp";

    export default {
        name: "SignIn",
        data() {
            return {
                email: "caden@test.com",
                password: "1234"
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
                this.$navigateTo(SignUp).catch(err => console.log(err));
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

</style>
