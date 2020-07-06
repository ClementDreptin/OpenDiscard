<template>
    <FlexboxLayout class="user-to-add" justifyContent="space-between" alignItems="center">
        <Image v-if="user.avatar_url"
               :src="`${axios.defaults.baseURL}${user.avatar_url}`"
               class="user-avatar"/>
        <Image v-else
               src="~/assets/default-profile-picture.png"
               class="user-avatar"/>
        <Label ref="label" :text="user.username"/>
        <Button ref="addButton" class="add-button" @tap="addUser"/>
    </FlexboxLayout>
</template>

<script>
    import errorHandler from "~/modules/Errors";

    export default {
        name: "UserToAdd",
        props: [
            'user'
        ],
        data() {
            return {
                axios: global.axios
            }
        },
        methods: {
            addUser() {
                global.axios.put(`/servers/${this.$store.state.currentServer.id}/users/${this.user.id}`)
                    .then(response => {
                        this.$store.state.currentServer.members.push(response.data.user);
                        this.disableButton();
                    }).catch(err => errorHandler(err, this));
            },

            disableButton() {
                this.$refs.addButton.nativeView.text = "Added";
                this.$refs.addButton.nativeView.isEnabled = false;
            }
        },
        mounted() {
            if (this.$store.state.currentServer.members.find(member => member.id === this.user.id)) {
                this.disableButton();
            } else {
                this.$refs.addButton.nativeView.text = "Add";
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../color-variables";

    .user-avatar {
        width: 48;
        height: 48;
    }

    Label {
        color: $white;
    }

    .add-button {
        height: 40;
        background-color: $grey-1;
        color: $success;
        border-color: $success;
        border-width: 2;
        border-radius: 5;
        font-size: 15;
        font-weight: 500;
    }
</style>
