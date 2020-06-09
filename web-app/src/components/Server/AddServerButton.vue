<template>
    <div>
        <div v-show="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="showModal = false">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Create your Server</p>
                        <button @click="showModal = false" class="delete"></button>
                    </header>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input class="input" type="text" name="server-name" placeholder="Name">
                            </p>
                        </div>
                        <div class="field">
                            <div class="file">
                                <label class="file-label">
                                    <input class="file-input" ref="file" @change="showMyImage()" type="file" name="resume">
                                    <span class="file-cta">
                                        <span class="file-icon">
                                            <i class="fas fa-upload"></i>
                                        </span>
                                        <span class="file-label">
                                            Choose an image...
                                        </span>
                                    </span>
                                    <span class="file-name">{{ fileName }}</span>
                                </label>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button @click="createServer();showModal = false" class="button is-success">Create</button>
                        <button @click="showModal = false" class="button">Cancel</button>
                    </footer>
                </form>
            </div>
        </div>
        <figure class="image is-48x48">
            <a @click="openModal">
                <div class="plus-sign">
                    <svg width="24" height="24" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M21 11.001H13V3.00098H11V11.001H3V13.001H11V21.001H13V13.001H21V11.001Z"></path>
                    </svg>
                </div>
            </a>
        </figure>
    </div>
</template>

<script>
    export default {
        name: "AddServerButton",
        data() {
            return {
                showModal: false,
                fileName: "",
                fileData: null
            }
        },
        methods: {
            openModal() {
                this.showModal = true;
            },

            createServer() {
                axios.post('/images/', {
                    image: this.fileData
                }).then(response => {
                    console.log(response.data);
                })
            },

            showMyImage() {
                let file = this.$refs.file.files[0];
                if (!file.type.match(/image.*/)) return;
                this.fileName = file.name;
                let reader = new FileReader();

                reader.readAsDataURL(file);

                reader.onload = () => {
                    this.fileData = reader.result.replace(/^data:image\/.*;base64,/, "");
                    console.log(this.fileData);
                };
            }
        }
    }
</script>

<style scoped>
    a {
        padding: 0;
        border-radius: 10px;
        width: 48px;
        height: 48px;
    }

    .plus-sign > svg {
        margin-top: 12px;
    }

    .plus-sign:hover {
        border-radius: 10px;
        background-color: #43b581;
        color: white;
    }

    .menu-list a:hover {
        background-color: transparent;
        color: transparent;
    }

    .plus-sign {
        color: #43b581;
        border-radius: 100%;
        width: 48px;
        height: 48px;
        background-color: #36393F;
        -moz-transition: all .3s;
        -o-transition: all .3s;
        -webkit-transition: all .3s;
        transition: all .3s;
    }
</style>