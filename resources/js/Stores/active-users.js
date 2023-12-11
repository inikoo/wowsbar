import { defineStore } from "pinia";

export const orgActiveUsers = defineStore("orgActiveUsers", {
    state: () => ({
        activeUsers: {}
    }),
    getters: {
        count: (state) => Object.keys(state.activeUsers).length
    },
    actions: {
        unsubscribe () {
            window.Echo.leave(`org.live.users`)
        },
        subscribe() {

            window.Echo.join(`org.live.users`)
            .here((rawUsers) => {
                console.log('Who is here: ', rawUsers)
                // on first load then store to 🍍

                let users={};
                rawUsers.forEach((user)=>{
                    users[user.id]=user;
                });


                this.activeUsers = users
            })
            .joining((user) => {
                console.log('Someone is join: ', user)
                // If another user join from another place
                this.activeUsers[user.id]=user;
            })
            .leaving((user) => {
                console.log('Someone leaved: ', user)
                // If another user leave
               // this.activeUsers.find(activeUser => activeUser.id === user.id).last_active = new Date()
                delete this.activeUsers[user.id];

            })
            .error((error) => {
                console.log('error', error)
            })
            .listen('.changePage', (data) => {

                console.log('Another user '+data.user_alias+'  is change the page '+data.active_page)
                // Listen from another user who change the page

                this.activeUsers[data.user_id].active_page = data.active_page


            })


        },
    },
});
