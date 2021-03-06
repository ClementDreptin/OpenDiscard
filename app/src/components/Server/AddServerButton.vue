<template>
    <div>
        <div v-if="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <ModalHeader title="Create your Server"/>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input class="input" type="text" v-model="serverName" placeholder="Name">
                            </p>
                        </div>
                        <InputFile/>
                        <ErrorBox/>
                    </section>
                    <ModalFooter :actionFunction="createServer" actionTitle="Create"/>
                </form>
            </div>
        </div>
        <figure class="image is-48x48">
            <a @click="showModal = true;resetInputs()">
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
    import InputFile from "../General/InputFile";
    import ErrorBox from "../General/ErrorBox";
    import ModalHeader from "../General/ModalHeader";
    import ModalFooter from "../General/ModalFooter";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "AddServerButton",
        components: {
            InputFile,
            ErrorBox,
            ModalHeader,
            ModalFooter
        },
        data() {
            return {
                showModal: false,
                serverName: "",
                fileName: "",
                fileData: null,
                fail: null
            }
        },
        methods: {
            createServer() {
                if (this.serverName === "") return this.fail = "You must give your server a name!";

                if (this.fileData) {
                    axios.post('/images/', {image: this.fileData})
                        .then(response => this.createServerApi(response.data.url))
                        .catch(err => errorHandler(err, this))
                } else {
                    this.createServerApi();
                }
            },

            createServerApi(imageUrl) {
                let params = { name: this.serverName };
                if (imageUrl) params.image_url = imageUrl;
                axios.post('/servers/', params)
                    .then(response => {
                        this.$store.state.servers.push(response.data.server);
                        this.$bus.$emit('serverAdded');
                        this.showModal = false;
                    })
                    .catch(err => errorHandler(err, this));
            },

            resetInputs() {
                this.serverName = "";
                this.fileName = "";
                this.fileData = null;
                this.fail = null;
            }
        }
    }
</script>

<style scoped>
    figure {
        margin-left: 3px;
    }

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