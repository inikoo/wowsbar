/**
 * Author: Vika Aqordi
 * Created on: 10-12-2024-08h-17m
 * Github: https://github.com/aqordeon
 * Copyright: 2024
*/

export interface Links {
    first: string
    last: string
    prev?: string
    next?: string
}

export interface Meta {
    current_page: number
    from: number
    last_page: number
    links: {
        url: string | null
        label: string
        active: boolean
    }[]
    path: string
    per_page: number
    to: number
    total: number
}

export interface Table {
    data: any[]
    meta: Meta
    links: Links
}