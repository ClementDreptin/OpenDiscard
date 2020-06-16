<template>
    <div id="user-info">
        <div v-show="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <ModalHeader title="User Settings"/>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input @keypress="fail = null" class="input" type="text" v-model="userLocal.username" placeholder="Username">
                            </p>
                        </div>
                        <InputFile/>
                        <ErrorBox/>
                    </section>
                    <footer class="modal-card-foot">
                        <button @click="updateUser" class="button is-success">Update</button>
                        <button @click="showModal = false" class="button">Cancel</button>
                    </footer>
                </form>
            </div>
        </div>
        <div class="user-icon">
            <div v-if="$store.state.user.avatar_url">
                <figure class="image is-32x32">
                    <img class="is-rounded"
                         :src="`${axios.defaults.baseURL}${$store.state.user.avatar_url}`"
                         :alt="`${$store.state.user.username}'s profile picture`">
                </figure>
            </div>
            <div v-else>{{ $store.state.user.username[0] }}</div>
        </div>
        <div class="user-name">{{ $store.state.user.username }}</div>
        <a @click="showModal = true;resetInputs()" class="user-settings">
            <i class="fa fa-cog"></i>
        </a>
    </div>
</template>

<script>
    import InputFile from "../General/InputFile";
    import ErrorBox from "../General/ErrorBox";
    import ModalHeader from "../General/ModalHeader";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "UserInfo",
        components: {
            InputFile,
            ErrorBox,
            ModalHeader
        },
        data() {
            return {
                axios: axios,
                showModal: false,
                fileName: "",
                fileData: null,
                fail: null,
                userLocal: JSON.parse(JSON.stringify(this.$store.state.user)), // Deep clone of the user
            }
        },
        methods: {
            updateUser() {
                if (this.userLocal.username === "") return this.fail = "You must give yourself a username!";

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
                        this.showModal = false;
                    })
                    .catch(err => errorHandler(err, this));
            },

            resetInputs() {
                this.userLocal = JSON.parse(JSON.stringify(this.$store.state.user));
                this.fail = null;
                this.fileName = "";
                this.fileData = null;
            }
        }
    }
</script>

<style scoped>
    #user-info {
        color: white;
        display: flex;
        background-color: #292b2f;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .user-icon, .user-name, .user-settings {
        margin-top: 0.4rem;
        margin-bottom: 0.4rem;
        margin-right: 0.4rem;
    }

    .user-icon {
        flex: 1;
        text-align: center;
    }

    .user-name {
        flex: 5;
        font-size: 0.9em;
        font-weight: 600;
        margin-top: 0.6em;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .user-settings {
        flex: 1;
        text-align: right;
        color: white;
        margin-right: 0;
    }
</style>