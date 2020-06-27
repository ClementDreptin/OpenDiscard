<template>
    <div class="user-to-add">
        <figure class="image is-32x32">
            <img v-if="user.avatar_url" class="is-rounded"
                 :src="`${axios.defaults.baseURL}${user.avatar_url}`"
                 :alt="`${user.username}'s profile picture`">
            <img v-else
                 src="../../assets/default-profile-picture.svg"
                 :alt="`${user.username}'s profile picture`">
        </figure>
        <div>{{ user.username }}</div>
        <button @click="addUser" ref="addButton" class="button is-success"></button>
        <ErrorModal/>
    </div>
</template>

<script>
    import ErrorModal from "../General/ErrorModal";

    export default {
        name: "UserToAdd",
        components: {
            ErrorModal
        },
        props: [
            'user'
        ],
        data() {
            return {
                axios: axios,
                fail: null
            }
        },
        methods: {
            addUser() {
                axios.put(`/servers/${this.$store.state.currentServer.id}/users/${this.user.id}`)
                    .then(response => {
                        this.$store.state.currentServer.members.push(response.data.user);
                        this.disableButton();
                    }).catch(err => this.$parent.fail = err.response.data.message);
            },

            disableButton() {
                this.$refs.addButton.innerText = "Added";
                this.$refs.addButton.disabled = true;
            }
        },
        mounted() {
            if (this.$store.state.currentServer.members.find(member => member.id === this.user.id)) {
                this.disableButton();
            } else {
                this.$refs.addButton.innerText = "Add";
            }
        }
    }
</script>

<style scoped>
    .user-to-add {
        display: flex;
        padding: 0.25em;
    }

    .user-to-add figure {
        margin-right: 1em;
    }

    .user-to-add div {
        flex: 10;
        padding: 0.5em;
    }

    .user-to-add button {
        flex: 2;
    }

    .button.is-success:disabled {
        background-color: transparent;
        border-width: 1px;
    }
</style>