import { usePage } from '@inertiajs/vue3'
import { defineStore } from 'pinia'
import { LiveUser, LiveUsers } from '@/types/OnlineUsers'

export const useCustOnlineUsers = defineStore('useCustOnlineUsers', {
    state: () => ({
        onlineUsers: {} as LiveUsers,
    }),
    getters: {
        count: (state) => Object.keys(state.onlineUsers).length,
    },
    actions: {
        unsubscribe() {
            window.Echo.leave(`cust.online.users`)
        },
        subscribe() {
            console.log(window.Echo.join(`cust.online.users`)
                .joining((user: LiveUser) => {
                    // console.log('Someone is join: ', user);
                    window.Echo.join(`cust.online.users`).whisper(`sendTo${user.id}`, this.onlineUsers[usePage().props.auth.user.id])
                })

                .leaving((user: LiveUser) => {
                    // console.log(user)
                    // If user 'logout', no need to set the action to 'leave'
                    if (this.onlineUsers?.[user.id]?.action != 'logout') {
                        // this.onlineUsers[user.id].action = 'leave'
                        this.onlineUsers[user.id].last_active = new Date()
                    }
                })

                .error((error: any) => {
                    console.log('error', error)
                })

                .listenForWhisper('otherIsNavigating', (e: LiveUser) => {
                    // On the first load and on navigating page
                    this.onlineUsers[e.id] = e
                })

                .listenForWhisper(`sendTo${usePage().props.auth.user.id}`, (otherUser: LiveUser) => {
                    // Receive data from others (that they know I join the channel)
                    this.onlineUsers[otherUser.id] = otherUser
                })
            )

        },
    },
})
