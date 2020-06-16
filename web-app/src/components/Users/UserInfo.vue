<template>
    <div id="user-info">
        <div v-show="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <header class="modal-card-head">
                        <p class="modal-card-title">User Settings</p>
                        <button @click="showModal = false" class="delete"></button>
                    </header>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input @keypress="fail = null" class="input" type="text" v-model="userLocal.username" placeholder="Username">
                            </p>
                        </div>
                        <div class="field">
                            <div class="file">
                                <label class="file-label">
                                    <input class="file-input" ref="file" @change="encodeImage" type="file" name="resume">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Choose an image...
                                        </span>
                                    </span>
                                </label>
                                <span v-show="fileName" class="file-name">{{ fileName }}</span>
                            </div>
                        </div>
                        <div class="container">
                            <article class="message is-danger" v-show="fail">
                                <div class="message-header">
                                    <p>Error</p>
                                </div>
                                <div class="message-body">
                                    {{ fail }}
                                </div>
                            </article>
                        </div>
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
        <a @click="showModal = true;resetUserName()" class="user-settings">
            <i class="fa fa-cog"></i>
        </a>
    </div>
</template>

<script>
    import errorHandler from "../../modules/Errors";

    export default {
        name: "UserInfo",
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
            encodeImage() {
                this.fail = null;
                let file = this.$refs.file.files[0];
                if (!file.type.match(/image.*/)) return this.fail = "You must select an image.";
                this.fileName = file.name;
                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = () => this.fileData = reader.result.replace(/^data:image\/.*;base64,/, "");
            },

            resetUserName() {
                this.userLocal = JSON.parse(JSON.stringify(this.$store.state.user));
                this.fail = null;
            },

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
        }
    }
</script>

<style scoped>
    #user-info {
        color: white;
        display: flex;
        //background-color: #292b2f;
        background-color: blue;
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
    }
</style>