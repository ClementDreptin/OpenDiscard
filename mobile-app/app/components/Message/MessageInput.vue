<template>
    <TextField v-model="messageContent" hint="Message"/>
</template>

<script>
    export default {
        name: "MessageInput",
        data() {
            return {
                messageContent: ""
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "../../color-variables";

    TextField {
        color: $white;
        font-size: 15;
        placeholder-color: #A8A8A8;
        background-color: $grey-1;
        width: 96%;
        padding: 9 13.5;
        border-color: #A8A8A8;
        border-width: 1;
        border-radius: 4;
    }
</style>
