<template>
    <aside class="menu">
        <ul class="menu-list">
            <li v-if="$store.state.currentServer">
                <AddMemberButton/>
            </li>
            <li v-for="member in members">
                <Member :member="member"/>
            </li>
        </ul>
        <ErrorModal/>
    </aside>
</template>

<script>
    import Member from "./Member";
    import AddMemberButton from "./AddMemberButton";
    import errorHandler from "../../modules/Errors";
    import ErrorModal from "../General/ErrorModal";

    export default {
        name: "Members",
        components: {
            ErrorModal,
            Member,
            AddMemberButton
        },
        data() {
            return {
                members: [],
                fail: null
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

            this.$bus.$on('currentServerWasDeleted', () => {
                this.members = [];
            });
        }
    }
</script>

<style scoped>
    aside {
        margin-bottom: 2em;
    }
</style>