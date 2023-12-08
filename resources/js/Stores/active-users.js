import { defineStore } from "pinia";

export const useEchoOrgPersonal = defineStore("echo-org-personal", {
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
