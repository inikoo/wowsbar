/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 16 Sept 2022 00:24:09 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Raul A Perusquia Flores
 */

// @ts-ignore
import ziggyRouteFunction from "@types/ziggy-js";

// Defines the function in all TS files and the script tags in Vue SFC.
declare global {
    const route: typeof ziggyRouteFunction;
}

// Defines the function in your vue templates.
// You can simply remove this if you are not using vue.
declare module "@vue/runtime-core" {
    interface ComponentCustomProperties {
        route: typeof ziggyRouteFunction;
    }
}
