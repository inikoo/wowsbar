/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 08 Dec 2023 02:34:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

import {defineStore} from 'pinia';

export const useEchoOrgGeneral = defineStore(
    'echo-action-progress',
    {

        state  : () => ({
            prospectsDashboard: {},
        }),
        actions: {

            subscribe(){
                window.Echo.private('org.general')
                .listen('.prospects-dashboards', (e) => {
                    console.log(e);

                });

            }
        },

    });
