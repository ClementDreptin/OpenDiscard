<template>
    <Page>
        <StackLayout class="modal-container">
            <TextField class="modal-input" v-model="userLocal.username" hint="Your username"/>
            <Button class="btn-success" text="Update" @tap="updateUser"/>
        </StackLayout>
    </Page>
</template>

<script>
    import errorHandler from "~/modules/Errors";

    export default {
        name: "UserInfoModal",
        data() {
            return {
                userLocal: JSON.parse(JSON.stringify(this.$store.state.user)), // Deep clone of the user
                fileData: null // For a possible future functionality where you'll be able to change your profile picture from the mobile app
            }
        },
        methods: {
            updateUser() {
                if (this.userLocal.username === "") return alert({
                    title: "Error",
                    message: "You must give yourself a username!",
                    okButtonText: "OK"
                });

                if (this.fileData) {
                    axios.post('/images/', {image: this.fileData})
                        .then(response => this.updateUserApi(response.data.url))
                        .catch(err => errorHandler(err, this))
                } else {
                    this.updateUserApi();
                }
            },

            updateUserApi(avatarUrl) {
                let params = { username: this.userLocal.username };
                if (avatarUrl) params.avatar_url = avatarUrl;

                axios.patch(`/users/${this.$store.state.user.id}`, params)
                    .then(response => {
                        this.$store.state.user.username = response.data.user.username;
                        this.$store.state.user.avatar_url = response.data.user.avatar_url;
                        this.$modal.close();
                    })
                    .catch(err => errorHandler(err, this));
            },
        }
    }
</script>

<style scoped lang="scss">

</style>
