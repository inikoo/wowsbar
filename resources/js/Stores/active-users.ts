import { usePage } from '@inertiajs/vue3'
import { defineStore } from 'pinia'
import { LiveUser, LiveUsers } from '@/types/OnlineUsers'

export const liveOrganisationUsers = defineStore('liveOrganisationUsers', {
    state: () => ({
        liveOrganisationUsers: {} as LiveUsers,
    }),
    getters: {
        count: (state) => Object.keys(state.liveOrganisationUsers).length,
    },
    actions: {
        unsubscribe() {
            window.Echo.leave(`org.live.users`)
        },
        subscribe() {
            const chanel = window.Echo.join(`org.live.users`)
                .joining((user: LiveUser) => {
                    // console.log('Someone is join: ', user);
                    window.Echo.join(`org.live.users`).whisper(`sendTo${user.id}`, this.liveOrganisationUsers[usePage().props.auth.user.id])
                })

                .leaving((user: LiveUser) => {
                    // console.log(user)
                    // If user 'logout', no need to set the action to 'leave'
                    if (this.liveOrganisationUsers?.[user.id]?.action != 'logout') {
                        // this.liveOrganisationUsers[user.id].action = 'leave'
                        this.liveOrganisationUsers[user.id].last_active = new Date()
                    }
                })

                .error((error: any) => {
                    console.log('error', error)
                })

                .listenForWhisper('otherIsNavigating', (e: LiveUser) => {
                    // On the first load and on navigating page 
                    this.liveOrganisationUsers[e.id] = e
                })

                .listenForWhisper(`sendTo${usePage().props.auth.user.id}`, (otherUser: LiveUser) => {
                    // Receive data from others (that they know I join the channel)
                    this.liveOrganisationUsers[otherUser.id] = otherUser
                })

                console.log('dfdfdf',chanel)

        },
    },
})
