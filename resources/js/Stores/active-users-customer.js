import { defineStore } from 'pinia';

export const liveCustomerUsers = defineStore('liveCustomerUsers', {
    state  : () => ({
        liveCustomerUsers: {
            // 1: {
            //     name: '',
            //     current_page: {
            //         label: ''
            //     }
            // }
        },
    }),
    getters: {
        count: (state) => Object.keys(state.liveCustomerUsers).length,
    },
    actions: {
        unsubscribe() {
            window.Echo.leave(`customer.live.users`);
        },
        subscribe() {
            window.Echo.join(`customer.live.users`)
            .here((rawUsers) => {
                // console.log('Who is here: ', rawUsers);

                axios.get(route('customer.models.live-customer-users-current-page.index'))
                    .then((response) => {
                        // console.log('lll', response.data)
                        this.liveCustomerUsers = response.data

                        Object.keys(this.liveCustomerUsers).forEach((userKey) => {
                            // Retrieve alias from Echo.here data
                            this.liveCustomerUsers[userKey].name = (rawUsers.find((rawUser) => rawUser.id == userKey))?.name
                        })
                    });

            }).joining((user) => {
                // console.log('Someone is join: ', user);
                this.liveCustomerUsers[user.id] = user

            }).leaving((user) => {
                // console.log('Someone leaved: ', user);
                delete this.liveCustomerUsers[user.id]

            }).error((error) => {
                console.log('error', error);

            }).listen('.changePage', (data) => {
                // Listen from another user who change the page
                this.liveCustomerUsers[data.user_id].active_page = data.active_page;

            });

        },
    },
});
