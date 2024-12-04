/**
 *  author: Vika Aqordi
 *  created on: 23-10-2024
 *  github: https://github.com/aqordeon
 *  copyright: 2024
*/

import AnnouncementPromo1 from '@/Components/Workshop/Announcement/Templates/Promo/AnnouncementPromo1.vue'
import AnnouncementPromo2Countdown from '@/Components/Workshop/Announcement/Templates/Promo/AnnouncementPromo2Countdown.vue'
import AnnouncementInformation1 from "@/Components/Workshop/Announcement/Templates/Information/AnnouncementInformation1.vue"
import type { Component } from "vue"


// Get the component based on code
export const getAnnouncementComponent = (code: string) => {
    const componentsList: Component = {
        'announcement-information-1': AnnouncementInformation1,
        'announcement-promo-1': AnnouncementPromo1,
        'announcement-promo-2-countdown': AnnouncementPromo2Countdown,
    }

    return componentsList[code]
}

// Component: Close icon
export const closeIcon = '<svg style="display: inline-block;height:1em;vertical-align:-0.125em;text-align: center;overflow: visible;box-sizing: content-box;width: 1.25em;" aria-hidden="true" focusable="false" data-prefix="fal" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><path class="" fill="currentColor" d="M193.94 256L296.5 153.44l21.15-21.15c3.12-3.12 3.12-8.19 0-11.31l-22.63-22.63c-3.12-3.12-8.19-3.12-11.31 0L160 222.06 36.29 98.34c-3.12-3.12-8.19-3.12-11.31 0L2.34 120.97c-3.12 3.12-3.12 8.19 0 11.31L126.06 256 2.34 379.71c-3.12 3.12-3.12 8.19 0 11.31l22.63 22.63c3.12 3.12 8.19 3.12 11.31 0L160 289.94 262.56 392.5l21.15 21.15c3.12 3.12 8.19 3.12 11.31 0l22.63-22.63c3.12-3.12 3.12-8.19 0-11.31L193.94 256z"></path></svg>'
