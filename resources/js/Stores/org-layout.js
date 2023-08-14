/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Mon, 14 Aug 2023 12:10:29 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

import { defineStore } from "pinia";

export const useOrgLayoutStore = defineStore("org-layout", {
    state: () => (
        {
            navigation            : [],
            shopsInDropDown       : {},
            organisation                : {},
            currentRoute          : "",
            currentRouteParameters: {},
            currentModule         : "",
            rightSidebar          : {
                activeUsers: false,
                language: false,
            },
            avatar_id:null
        }
    )

});

