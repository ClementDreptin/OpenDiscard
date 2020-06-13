<template>
    <aside class="menu" v-if="$store.state.currentServer">
        <header>
            <h6 class="title is-6">{{ $store.state.currentServer.name }}</h6>
        </header>
        <ul class="menu-list">
            <li>
                <AddTextChannelButton/>
            </li>
            <li v-for="textChannel in textChannels">
                <TextChannel :textChannel="textChannel"/>
            </li>
        </ul>
    </aside>
</template>

<script>
    import TextChannel from "./TextChannel";
    import AddTextChannelButton from "./AddTextChannelButton";

    export default {
        name: "TextChannels",
        components: {
            TextChannel,
            AddTextChannelButton
        },
        data() {
            return {
                textChannels: []
            }
        },
        methods: {
            getTextChannels() {
                axios.get(`/servers/${this.$store.state.currentServer.id}/channels/`)
                    .then(response => {
                        this.$store.state.currentServer.textChannels = response.data.text_channels;
                        this.textChannels = this.$store.state.currentServer.textChannels;
                        if (this.textChannels.length !== 0) {
                            this.$store.state.currentTextChannel = this.textChannels[0];
                            this.$bus.$emit('currentTextChannelChanged');
                        }
                    }).catch(err => console.log(err.response.data.message));
            }
        },
        mounted() {
            this.$bus.$on('currentServerChanged', () => {
                this.getTextChannels();
            });

            this.$bus.$on('textChannelAdded', () => {
                this.getTextChannels();
            });
        }
    }
</script>

<style scoped>
    aside {
        margin-bottom: 2em;
    }

    h6 {
        color: #dcddde;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        padding-bottom: 1.25rem;
        padding-top: 0.5rem;
        border-bottom: solid 2px #272727;
    }
</style>