<template>
    <div v-show="$parent.showConfirmDeleteModal" class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">Delete Text Channel</p>
                <button @click="$parent.showConfirmDeleteModal = false" class="delete"></button>
            </header>
            <section class="modal-card-body">
                Are you sure you want to delete this Text Channel? This cannot be undone.
            </section>
            <footer class="modal-card-foot">
                <button @click="deleteTextChannel" class="button is-danger">Delete</button>
                <button @click="$parent.showConfirmDeleteModal = false" class="button">Cancel</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DeleteTextChannelModal",
        methods: {
            deleteTextChannel() {
                console.log(this.$parent.showConfirmDeleteModal);
            }
        }
    }
</script>

<style scoped>
    .modal-card {
        max-width: 50%;
    }
</style>