/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 08 Dec 2023 01:34:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

import { useMilisecondToTime } from "@/Composables/useFormatTime"
import { differenceInMilliseconds } from 'date-fns/esm'
import { defineStore } from "pinia";

// interface ProgressBar {
//     [key: string]: {
//         [key: string]: {
//             action_id: number
//             action_type: string
//             data: {
//                 number_fails: number
//                 number_success: number
//             },
//             done: number
//             total: number
//         }
//     }
// }

export const useEchoOrgPersonal = defineStore("echo-org-personal", {
    state: () => ({
        progressBars: {
            // Upload: {
            //     1: {
            //         action_id: 0,
            //         action_type: '',
            //         data: {
            //             number_fails: 0,
            //             number_success: 0
            //         },
            //         done: 0,
            //         total: 0
            //     }
            // }
        },
        isShowProgress: false,
        recentlyUploaded: []
    }),
    actions: {
        subscribe(userID) {
            window.Echo.private("org.personal." + userID).listen(
                ".action-progress",
                (eventData) => {
                    // console.log(eventData)

                    // Update the state
                    this.$patch({
                        progressBars: {
                            [eventData.action_type]: {
                                [eventData.action_id]: {
                                    ...eventData,
                                    time_echo: new Date(),
                                    estimatedTime: useMilisecondToTime(differenceInMilliseconds(new Date(), this.progressBars?.[eventData.action_type]?.[eventData.action_id]?.time_echo ?? new Date()) * (eventData.total-eventData.done))
                                    // useEstimatedTime(new Date(), (this.progressBars?.[eventData.action_type]?.[eventData.action_id]?.time_echo ?? 0))
                                }
                            }
                        }
                    })

                    // console.log(this.progressBars[eventData.action_type][eventData.action_id].estimatedTime)

                    // To show the progress bars
                    if(!this.isShowProgress) this.isShowProgress = true

                    // If already reach 100%
                    if(eventData.done >= eventData.total){
                        // Add data to recentlyUploaded, to show in history
                        this.recentlyUploaded.push(this.progressBars[eventData.action_type][eventData.action_id])
                        
                        // Delete data in 4 seconds after finish
                        setTimeout(() => {
                            delete this.progressBars[eventData.action_type][eventData.action_id]

                            // If no more progress, then hide the bar
                            const uploadCount = Object.values(this.progressBars[eventData.action_type])
                            if(!uploadCount.length) this.isShowProgress = false 
                        }, 4000)
                    }
                }
            );
        },
    },
});
