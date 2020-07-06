<template>
    <Page>
        <StackLayout class="modal-container">
            <TextField class="modal-input" v-model="username" hint="Username"/>
            <ActivityIndicator v-if="busy" color="#dcddde" :busy="busy"/>
            <ScrollView>
                <StackLayout v-if="filteredUsers.length > 0" class="modal-content">
                    <UserToAdd v-for="user in filteredUsers" :key="user.id" :user="user"/>
                </StackLayout>
                <StackLayout v-else class="modal-content">
                    <Label text="Nobody was found..." class="username" fontSize="15" color="#A8A8A8"/>
                </StackLayout>
            </ScrollView>
            <Button class="btn-success" text="Done" @tap="$modal.close()"/>
        </StackLayout>
    </Page>
</template>

<script>
    import UserToAdd from "~/components/User/UserToAdd";
    import errorHandler from "~/modules/Errors";

    export default {
        name: "AddMemberModal",
        components: {
            UserToAdd
        },
        data() {
            return {
                username: "",
                users: [],
                filteredUsers: [],
                busy: false
            }
        },
        methods: {
            searchUsers() {
                switch (this.username.trim().length) {
                    case 0:
                        this.users = [];
                        this.filteredUsers = [];
                        break;
                    case 1:
                        this.busy = true;
                        global.axios.get(`/users?elem=${this.username.trim()}`)
                            .then(response => {
                                this.busy = false;
                                this.users = response.data.users;
                                this.filteredUsers = this.users;
                            }).catch(err => errorHandler(err, this));
                        break;
                    default:
                        this.filteredUsers = this.users.filter(user => user.username.toLowerCase().startsWith(this.username.toLowerCase()));
                        break;
                }
            }
        },
        watch: {
            username: function() {
                this.searchUsers();
            }
        }
    }
</script>

<style scoped lang="scss">
    .modal-content {
        padding: 12;
    }
</style>
