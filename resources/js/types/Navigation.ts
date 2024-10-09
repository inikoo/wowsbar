/**
 *  author: Vika Aqordi
 *  created on: 09-10-2024
 *  github: https://github.com/aqordeon
 *  copyright: 2024
*/

import { routeType } from "@/types/route"

export interface DropdownLink {
    label?: string
    tooltip: string
    icon: string[]
    route: {
        all: string[]
        selected: string[]
    }
}

export interface SubSection {
    label: string
    icon?: string | string[]
    tooltip?: string
    root: string
    route: routeType
}

export interface Navigation {
    root?: string  // For Navigation active state purpose
    label: string
    tooltip?: string
    scope?: string
    icon?: string[] | string
    route?: routeType
    topMenu?: {
        subSections?: SubSection[]
        dropdown?: {
            links: DropdownLink[]
        }
    }
}

export interface grpNavigation {
    [key: string]: Navigation
}

export interface orgNavigation {
    [orgKey: string]: {
        [shopKey: string]: {
            [navShopKey: string]: {
                type?: string  // 'shop' || 'dropshipping'
                subNavigation: Navigation
            }
        }
    }
}