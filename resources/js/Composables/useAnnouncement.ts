/**
 *  author: Vika Aqordi
 *  created on: 23-10-2024
 *  github: https://github.com/aqordeon
 *  copyright: 2024
*/

import AnnouncementPromo1 from '@/Components/Workshop/Announcement/Templates/Promo/AnnouncementPromo1.vue'
import AnnouncementInformation1 from "@/Components/Workshop/Announcement/Templates/Information/AnnouncementInformation1.vue"
import type { Component } from "vue"



export const getAnnouncementComponent = (code: string) => {
    const componentsList: Component = {
        'announcement-information-1': AnnouncementInformation1,
        'announcement-promo-1': AnnouncementPromo1
    }

    return componentsList[code]
}