<template>
    <aside class="menu">
        <ul class="menu-list">
            <li v-for="member in members">
                <Member :member="member"/>
            </li>
        </ul>
    </aside>
</template>

<script>
    import Member from "./Member";
    import errorHandler from "../../modules/Errors";

    export default {
        name: "Members",
        components: {
            Member
        },
        data() {
            return {
                members: []
            }
        },
        methods: {
            getMembers() {
                if (!this.$store.state.currentServer.members) {
                    axios.get(`/servers/${this.$store.state.currentServer.id}/users/`)
                        .then(response => {
                            this.$store.state.currentServer.members = response.data.members;
                            this.members = this.$store.state.currentServer.members;
                        }).catch(err => errorHandler(err, this));
                } else {
                    this.members = this.$store.state.currentServer.members;
                }
            }
        },
        mounted() {
            this.$bus.$on('currentServerChanged', () => {
                this.getMembers();
            });
        }
    }
</script>

<style scoped>
    aside {
        margin-bottom: 2em;
    }
</style>