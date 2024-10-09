/**
 *  author: Vika Aqordi
 *  created on: 09-10-2024
 *  github: https://github.com/aqordeon
 *  copyright: 2024
*/

import { routeType } from '@/types/route'
export interface Notification {
    id: string
    read: boolean
    route?: routeType
    href: string //  "http://app.aiku.test/org/aw/fulfilments/awf/customers/3b-recycling-ltd/pallet-deliveries/brl-001"
    title: string
    body: string
    created_at: Date | string
}