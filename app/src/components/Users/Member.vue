<template>
    <div>
        <span>
            <figure class="image is-32x32">
                <img v-if="member.avatar_url" class="is-rounded"
                     :src="`${axios.defaults.baseURL}${member.avatar_url}`"
                     :alt="`${member.username}'s profile picture`">
                <img v-else
                     src="../../assets/default-profile-picture.svg"
                     :alt="`${member.username}'s profile picture`">
            </figure>
        </span>
        <span class="member-name">{{ member.username }}</span>
        <a v-if="$store.state.currentServer && $store.state.currentServer.owner_id === $store.state.user.id && $store.state.currentServer.owner_id !== member.id"
           class="kick-button"
           @click="showConfirmDeleteModal = true">
            <span>
                <i class="fa fa-times"></i>
            </span>
        </a>
        <ConfirmDeleteModal element="User" actionTitle="Remove" :deleteFunction="removeUser"/>
        <ErrorModal/>
    </div>
</template>

<script>
    import ConfirmDeleteModal from "../General/ConfirmDeleteModal";
    import ErrorModal from "../General/ErrorModal";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "Member",
        components: {
            ErrorModal,
            ConfirmDeleteModal
        },
        props: [
            'member'
        ],
        data() {
            return {
                axios: axios,
                showConfirmDeleteModal: false,
                fail: null
            }
        },
        methods: {
            removeUser() {
                axios.delete(`/servers/${this.$store.state.currentServer.id}/users/${this.member.id}`)
                    .then(response => {
                        let members = this.$store.state.currentServer.members;
                        let index = members.findIndex(member => member.id === response.data.user.id);

                        members.splice(index, 1);

                        this.showConfirmDeleteModal = false;
                    }).catch(err => errorHandler(err, this));
            }
        }
    }
</script>

<style scoped>
    div {
        display: flex;
        padding: 0.4em 0;
    }

    span {
        color: #a5a5a5;
        margin-top: 0.4em;
        margin-bottom: 0.4em;
        margin-right: 0.4em;
    }

    div:hover > span {
        color: white;
    }

    div:hover > .kick-button > span {
        color: #c92142;
        opacity: 0.4;
    }

    .member-name {
        flex-grow: 1;
        margin-top: 0.6em;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .kick-button {
        text-align: right;
        padding-right: 0;
        padding-top: 0.65em;
    }

    .kick-button:hover {
        background-color: transparent;
    }

    .kick-button:hover > span {
        opacity: 1 !important;
    }

    .kick-button > span {
        margin: 0;
        color: transparent;
    }
</style>