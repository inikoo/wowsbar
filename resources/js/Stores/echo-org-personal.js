/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 08 Dec 2023 01:34:47 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

import {defineStore} from 'pinia';

export const useEchoOrgPersonal = defineStore(
    'echo-action-progress',
    {

        state  : () => ({
            progressBars: {},
        }),
        actions: {

            subscribe(userID){
                window.Echo.private('org.personal.'+userID)
                .listen('.action-progress', (e) => {
                    console.log(e);
                    //save event data in state.progressBars
                    // as an object  key->[e.action_type+'_'+e.action_id]= value -> e
                    // then use this store to display progress data
                });

            }
        },

    });
