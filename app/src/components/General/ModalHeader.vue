<template>
    <header class="modal-card-head">
        <p class="modal-card-title">{{ title }}</p>
        <button v-if="$parent.showModal" @click="$parent.showModal = false" class="delete"></button>
    </header>
</template>

<script>
    export default {
        name: "ModalHeader",
        props: [
            'title'
        ]
    }
</script>

<style scoped>
    @media screen and (max-width:430px) {
        .modal-card-title{
            font-size: 1em;
        }
    }
</style>