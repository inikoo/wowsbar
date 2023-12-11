import {defineStore} from 'pinia';
import {usePage} from '@inertiajs/vue3';

export const liveOrganisationUsers = defineStore('liveOrganisationUsers', {
    state  : () => ({
        liveOrganisationUsers: {},
    }),
    getters: {
        count: (state) => Object.keys(state.liveOrganisationUsers).length,
    },
    actions: {
        unsubscribe() {
            window.Echo.leave(`org.live.users`);
        },
        subscribe() {

            window.Echo.join(`org.live.users`).here((rawUsers) => {
                console.log('Who is here: ', rawUsers);
                // on first load then store to 🍍

                axios.get(route(
                    'org.models.live-organisation-users-current-page.index')).
                    then((response) => {
                        console.log(response.data);
                        for (let userId in response.data) {
                            if (response.data.hasOwnProperty(userId)) {
                                console.log(response.data[userId]);
                            }
                        }

                        let users = {};
                        rawUsers.forEach((user) => {

                            if (response.data.hasOwnProperty(user.id)) {
                                user['last_active'] = response.data[user.id].last_active;
                                user['current_page'] = response.data[user.id].current_page;
                            }

                            console.log(user);
                            users[user.id] = user;
                        });

                        this.liveOrganisationUsers = users;
                    });

            }).joining((user) => {
                console.log('Someone is join: ', user);
                // If another user join from another place
                this.liveOrganisationUsers[user.id] = user;
            }).leaving((user) => {
                console.log('Someone leaved: ', user);
                // If another user leave
                // this.liveOrganisationUsers.find(activeUser => activeUser.id
                // === user.id).last_active = new Date()
                delete this.liveOrganisationUsers[user.id];

            }).error((error) => {
                console.log('error', error);
            }).listen('.changePage', (data) => {

                console.log('Another user ' + data.user_alias +
                                '  is change the page ' + data.active_page);
                // Listen from another user who change the page

                this.liveOrganisationUsers[data.user_id].active_page = data.active_page;

            });

        },
    },
});
