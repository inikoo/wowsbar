import { Image } from '@/types/Image'

export interface LiveUser {
    id: number
    username: string  // aiku
    avatar_thumbnail: Image
    name: string  // Mr. Aiku
    action: string  // navigate, leave, logout
    last_active: Date
    current_page?: {
        label: string,
        url: string
        icon_left: {
            icon: string | string[]
            class: string
        }
        icon_right: {
            icon: string | string[]
            class: string
        }
    }
}

export interface LiveUsers {
    [key: number]: LiveUser
}