<template lang="html">
    <Page>
        <ActionBar>
            <Label text="Members"></Label>
        </ActionBar>

        <StackLayout id="members-container">
            <ScrollView v-if="$store.state.currentServer">
                <StackLayout id="members">
                    <FlexboxLayout class="members-header" justifyContent="space-between">
                        <Label width="80%"
                               text="Members"
                               class="members-header-text"/>
                        <Image v-if="$store.state.currentServer.owner_id === $store.state.user.id"
                               @tap="showAddModal"
                               src.decode="font://&#xf067;"
                               class="fas members-header-button"
                               stretch="none"
                               fontSize="18"
                               opacity="0.5"/>
                    </FlexboxLayout>
                    <Member v-for="member in members" :member="member"/>
                </StackLayout>
            </ScrollView>
        </StackLayout>
    </Page>
</template>

<script>
    import Member from "~/components/User/Member";
    import errorHandler from "~/modules/Errors";

    export default {
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
                    global.axios.get(`/servers/${this.$store.state.currentServer.id}/users/`)
                        .then(response => {
                            this.$store.state.currentServer.members = response.data.members;
                            this.members = this.$store.state.currentServer.members;
                        }).catch(err => errorHandler(err, this));
                } else {
                    this.members = this.$store.state.currentServer.members;
                }
            },

            showAddModal() {

            }
        },
        mounted() {
            global.bus.$on('currentServerChanged', () => {
                this.getMembers();
            });

            global.bus.$on('currentServerWasDeleted', () => {
                this.members = [];
            });
        }
    }
</script>

<style lang="scss" scoped>
    @import "../color-variables";

    #members {
        padding: 18;

        &-container {
            background-color: $grey-1;
        }
    }

    .members-header {
        margin-bottom: 18;

        &-text {
            color: #7a7a7a;
            font-size: 13.5;
            letter-spacing: 0.2;
            text-transform: uppercase;
        }

        &-button {
            color: $white;
        }
    }
</style>
