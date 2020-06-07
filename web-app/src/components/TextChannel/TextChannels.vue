<template>
    <aside id="text-channels" class="menu">
        <ul class="menu-list">
            <li v-for="textChannel in textChannels">
                <TextChannel :textChannel="textChannel"/>
            </li>
        </ul>
    </aside>
</template>

<script>
    import TextChannel from "./TextChannel";

    export default {
        name: "TextChannels",
        components: {
            TextChannel
        },
        data() {
            return {
                textChannels: []
            }
        },
        methods: {
            getTextChannels() {
                if (!this.$store.state.currentServer.textChannels) {
                    axios.get(`/servers/${this.$store.state.currentServer.id}/channels/`)
                        .then(response => {
                            this.$store.state.currentServer.textChannels = response.data.text_channels;
                            this.textChannels = this.$store.state.currentServer.textChannels;
                            this.$store.state.currentTextChannel = this.textChannels[0];
                            this.$bus.$emit('currentTextChannelChanged');
                        }).catch(err => console.log(err.response.data.message));
                } else {
                    this.textChannels = this.$store.state.currentServer.textChannels;
                }
            }
        },
        mounted() {
            this.$bus.$on('currentServerChanged', () => {
                this.getTextChannels();
            })
        }
    }
</script>

<style scoped>

</style>