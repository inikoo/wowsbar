import { routeType } from '@/types/route'

export interface Action {
    type?: string  // undefined (button) | button | buttonGroup
    icon?: string | string[]
    label?: string
    iconRight?: string | string[]
    style?: string
    route?: routeType
    
    action: {
        // If type = buttonGroup
        icon?: string | string[]
        label?: string
        iconRight?: string | string[]
        style?: string
        route?: routeType

    }
}