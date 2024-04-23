/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Aug 2022 00:33:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

import { defineStore } from "pinia";
import { useColorTheme } from '@/Composables/useStockList'

export const useLayoutStore = defineStore("layout", {
    state: () => ({
        app: {
            // For App of the user 
            slug: "",
            name: "",
            showLiveUsers: false,
            // TODO create new Org default color
            theme: useColorTheme[0] as string[],  // For styling app color
            logo: {},
            url: "",
        },
        avatar_thumbnail: {
            original: "",
            original_2x: "",
            avif: "",
            avif_2x: "",
            webp: "",
            webp_2x: "",
        },
        booted: false,
        currentRoute: "",
        currentRouteParameters: {},
        currentModule: "",
        currentParentModule: "",
        leftSidebar: {
            show: true,
        },
        navigation: [
            {
                subNav: [],
                scope: "",
                icon: [],
                label: "",
                route: "",
                topMenu: {
                    subSections: [],
                },
            },
        ],
        organisation: {},
        rightSidebar: {
            activeUsers: {
                users: [],
                count: 0,
                show: false,
            },
            language: {
                show: false,
            },
        },
        systemName: '',  // For styling navigation depend on which App
        user: {
            username: "",
            name: "",
            avatar_thumbnail: null,
            customer_slug: null,
            customer_name: null,
        },
    }),
});
