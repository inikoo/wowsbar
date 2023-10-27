import { Button } from '@/types/Button'

export interface PageHeading {
    title: string
    actions: {
        type: string
        buttons: Button[]
    }[]
}