<template>
    <Page actionBarHidden="true">
        <FlexboxLayout class="sign-form">
            <StackLayout class="form">
                <Image class="logo" src="res://icon" />
                <Label class="header" text="OpenDiscard"/>

                <StackLayout class="input-field" marginBottom="25">
                    <TextField :isEnabled="!busy" class="input" hint="Email" keyboardType="email" autocorrect="false" autocapitalizationType="none" v-model="email" returnKeyType="next" fontSize="18" />
                    <StackLayout class="hr-light" />
                </StackLayout>

                <StackLayout class="input-field" marginBottom="25">
                    <TextField :isEnabled="!busy" ref="password" class="input" hint="Password" secure="true" v-model="password" fontSize="18" />
                    <StackLayout class="hr-light" />
                </StackLayout>

                <Button :isEnabled="!busy" text="Sign In" @tap="signIn" class="btn" />

                <ActivityIndicator color="#dcddde" :busy="busy"/>
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
    import errorHandler from "~/modules/Errors";

    export default {
        name: "SignIn",
        data() {
            return {
                email: "",
                password: "",
                busy: false
            }
        },
        methods: {
            signIn() {
                this.busy = true;
                global.axios.post('/users/signin/', {}, {
                    auth: {
                        username: this.email,
                        password: this.password
                    }
                }).then(response => {
                    this.busy = false;
                    this.$store.state.user = response.data.user;

                    this.$navigateTo(Home, {
                        animated: true,
                        transition: {
                            name: 'slide',
                            duration: 400,
                            curve: 'ease'
                        }
                    }).catch(err => console.log(err));
                }).catch(err => errorHandler(err, this));
            },

            goToSignUp() {
                this.$navigateTo(SignUp, {
                    animated: true,
                    clearHistory: true,
                    transition: {
                        name: 'slide',
                        duration: 400,
                        curve: 'ease'
                    }
                }).catch(err => console.log(err));
            }
        },
        mounted() {
            if (this.$store.state.user) {
                this.$navigateTo(Home, {
                    animated: true,
                    transition: {
                        name: 'slide',
                        duration: 400,
                        curve: 'ease'
                    }
                }).catch(err => console.log(err));
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../color-variables";

    Page {
        background-color: $grey-1;
    }

    .btn {
        background-color: $grey-1;
    }

    :disabled {
        opacity: 0.5;
    }
</style>
