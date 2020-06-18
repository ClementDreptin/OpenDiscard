<template>
    <div v-if="$parent.showConfirmDeleteModal" class="modal is-active">
        <div class="modal-background"></div>
        <div class="modal-card">
            <header class="modal-card-head">
                <p class="modal-card-title">{{ actionTitle }} {{ element }}</p>
                <button @click="$parent.showConfirmDeleteModal = false" class="delete"></button>
            </header>
            <section class="modal-card-body">
                Are you sure you want to {{ actionTitle.toLowerCase() }} this {{ element }}? This cannot be undone.
            </section>
            <footer class="modal-card-foot">
                <button @click="deleteFunction" class="button is-danger">{{ actionTitle }}</button>
                <button @click="$parent.showConfirmDeleteModal = false" class="button">Cancel</button>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DeleteTextChannelModal",
        props: [
            'element',
            'actionTitle',
            'deleteFunction'
        ]
    }
</script>

<style scoped>
    .modal-card {
        max-width: 50%;
    }
</style>