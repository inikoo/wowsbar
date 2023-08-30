/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 25 Aug 2022 00:33:39 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia F
 */

import { defineStore } from "pinia";

export const useLayoutStore = defineStore("layout", {
    state: () => (
        {
            booted:  false,
            navigation            : [],
            tenant                : {},
            currentRoute          : "",
            currentRouteParameters: {},
            currentModule         : "",
            rightSidebar: {
                activeUsers: {
                    users: [],
                    count: 0,
                    show: false
                },
                language: {
                    show: false
                },
            },
            avatar_thumbnail: null,
            organisation: {
            },
            user: {
                username: '',
                name: '',
                avatar: {
                    id: ''
                }

            }
        }
    )

});

