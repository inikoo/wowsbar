/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Sat, 05 Aug 2023 00:23:06 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

export interface Hero {
    whatsNew?: {
        label: string
        route: {
            name: string
            parameters: string
        }
    },
    title: string
    text?: string
    media: {
        logo: {
            original: string,
            webp?: string
        }
        appScreenshot: {
            original: string,
            webp?: string
        }
    }



}
