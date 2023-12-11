import {defineStore} from 'pinia';
// import {usePage} from '@inertiajs/vue3';

export const liveOrganisationUsers = defineStore('liveOrganisationUsers', {
    state  : () => ({
        liveOrganisationUsers: {
            1: {
                name: '',
                current_page: {
                    label: ''
                }
            }
        },
    }),
    getters: {
        count: (state) => Object.keys(state.liveOrganisationUsers).length,
    },
    actions: {
        unsubscribe() {
            window.Echo.leave(`org.live.users`);
        },
        subscribe() {
            window.Echo.join(`org.live.users`)
            .here((rawUsers) => {
                // console.log('Who is here: ', rawUsers);

                axios.get(route('org.models.live-organisation-users-current-page.index'))
                    .then((response) => {
                        // console.log('lll', response.data)
                        this.liveOrganisationUsers = response.data

                        Object.keys(this.liveOrganisationUsers).forEach((userKey) => {
                            // Retrieve alias from Echo.here data
                            this.liveOrganisationUsers[userKey].name = (rawUsers.find((rawUser) => rawUser.id == userKey))?.name
                        })
                    });

            }).joining((user) => {
                // console.log('Someone is join: ', user);
                this.liveOrganisationUsers[user.id] = user

            }).leaving((user) => {
                // console.log('Someone leaved: ', user);
                delete this.liveOrganisationUsers[user.id]

            }).error((error) => {
                console.log('error', error);

            }).listen('.changePage', (data) => {
                // Listen from another user who change the page
                // console.log('Another user ' + data.user_alias + '  is change the page ' + data.active_page);
                this.liveOrganisationUsers[data.user_id].active_page = data.active_page;

            });

        },
    },
});
