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
            window.Echo.leave(org.live.users)
        },
        subscribe() {

            window.Echo.join(`org.live.users`)
            .here((rawUsers) => {
                // on first load then store to 🍍

                let users={};
                rawUsers.forEach((user)=>{
                    users[user.id]=user;
                });


                this.activeUsers = users
            })
            .joining((user) => {
                // If another user join from another place
                //this.activeUsers.find(activeUser => activeUser.id === user.id).last_active = null
                this.activeUsers[user.id]=user;
            })
            .leaving((user) => {
                // If another user leave
               // this.activeUsers.find(activeUser => activeUser.id === user.id).last_active = new Date()
                delete this.activeUsers[user.id];
                window.Echo.leave('org.live.users') 
            })
            .error((error) => {
                console.log('error', error)
            })
            .listen('.changePage', (data) => {
                // Listen from another user who change the page
                this.activeUsers.find(activeUser => activeUser.id === data.user.id).active_page = data.data.active_page
                this.activeUsers.find(activeUser => activeUser.id === data.user.id).last_active = null
            })


        },
    },
});
