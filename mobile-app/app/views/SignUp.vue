<template>
    <Page actionBarHidden="true">
        <FlexboxLayout class="sign-form">
            <StackLayout class="form">
                <Image class="logo" src="res://icon" />
                <Label class="header" text="OpenDiscard" />

                <StackLayout class="input-field" marginBottom="25">
                    <TextField class="input" hint="Username" autocorrect="false" autocapitalizationType="none" v-model="username" returnKeyType="next" fontSize="18" />
                    <StackLayout class="hr-light" />
                </StackLayout>

                <StackLayout class="input-field" marginBottom="25">
                    <TextField class="input" hint="Email" keyboardType="email" autocorrect="false" autocapitalizationType="none" v-model="email" returnKeyType="next" fontSize="18" />
                    <StackLayout class="hr-light" />
                </StackLayout>

                <StackLayout class="input-field" marginBottom="25">
                    <TextField class="input" hint="Password" keyboardType="password" autocorrect="false" autocapitalizationType="none" v-model="password" returnKeyType="next" fontSize="18" />
                    <StackLayout class="hr-light" />
                </StackLayout>

                <StackLayout class="input-field" marginBottom="25">
                    <TextField class="input" hint="Retype password" keyboardType="password" autocorrect="false" autocapitalizationType="none" v-model="passwordVerify" fontSize="18" />
                    <StackLayout class="hr-light" />
                </StackLayout>

                <Button text="Sign Up" @tap="signUp" class="btn" />
            </StackLayout>

            <Label class="switch-page-label" @tap="goToSignIn">
                <FormattedString>
                    <Span text="Already have an account? Log In" />
                </FormattedString>
            </Label>
        </FlexboxLayout>
    </Page>
</template>

<script>
    import SignIn from "~/views/SignIn";

    export default {
        name: "SignUp",
        data() {
            return {
                username: "",
                email: "",
                password: "",
                passwordVerify: ""
            }
        },
        methods: {
            signUp() {
                if (this.password !== this.passwordVerify) {
                    return alert({
                        title: "Error",
                        message: "Passwords must be identical.",
                        okButtonText: "OK"
                    });
                }

                if (!this.username || !this.email || !this.password) {
                    return alert({
                        title: "Error",
                        message: "At least one of the fields is empty.",
                        okButtonText: "OK"
                    });
                }

                return alert({
                    title: "Success",
                    message: "Success",
                    okButtonText: "OK"
                });
            },

            goToSignIn() {
                this.$navigateTo(SignIn).catch(err => console.log(err));
            }
        }
    }
</script>

<style scoped lang="scss">

</style>
