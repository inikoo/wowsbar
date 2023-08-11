/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 11 Aug 2023 16:36:01 Malaysia Time, Pantai Lembeng, Bali
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

import { defineStore } from 'pinia'

export const useFirebaseStore = defineStore('firebase', {
    state: () => ({
        credentials: null
    }),

})

