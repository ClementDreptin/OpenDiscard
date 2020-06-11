<template>
    <div>
        <div v-show="showModal" class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-card">
                <form @submit.prevent="">
                    <header class="modal-card-head">
                        <p class="modal-card-title">Text Channel Options</p>
                        <button @click="showModal = false" class="delete"></button>
                    </header>
                    <section class="modal-card-body">
                        <div class="field">
                            <p class="control">
                                <input @keypress="fail = null" class="input" type="text" v-model="newTextChannelName" placeholder="Name">
                            </p>
                        </div>
                        <button @click="showConfirmDeleteModal = true" class="button is-danger is-pulled-right">
                            Delete this text channel
                        </button>
                        <DeleteTextChannelModal/>
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
                        <button @click="updateTextChannel" class="button is-success">Update</button>
                        <button @click="showModal = false" class="button">Cancel</button>
                    </footer>
                </form>
            </div>
        </div>
        <a @click="showModal = true">
            <i class="fa fa-cog"></i>
        </a>
    </div>
</template>

<script>
    import DeleteTextChannelModal from "./DeleteTextChannelModal";

    export default {
        name: "TextChannelSettings",
        components: {
            DeleteTextChannelModal
        },
        props: [
            'textChannel'
        ],
        data() {
            return {
                showModal: false,
                showConfirmDeleteModal: false,
                newTextChannelName: this.textChannel.name,
                fail: null
            }
        },
        methods: {
            updateTextChannel() {

            }
        }
    }
</script>

<style scoped>
    a {
        color: inherit;
        text-align: right;
        padding-left: 0;
        padding-right: 0;
    }

    a:hover {
        background-color: #2f3136;
        color: white;
    }
</style>