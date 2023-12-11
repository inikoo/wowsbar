import { defineStore } from "pinia";

export const orgActiveUsers = defineStore("orgActiveUsers", {
    state: () => ({
        activeUsers: [
            {
                name: '',
                username: '',
                last_active: '',
                active_page: ''
            }
        ]
    }),
    actions: {
        subscribe(userID) {
            
        },
    },
});
