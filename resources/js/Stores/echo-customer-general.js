/*
 * Author: Raul Perusquia <raul@inikoo.com>
 * Created: Fri, 08 Dec 2023 02:34:14 Malaysia Time, Kuala Lumpur, Malaysia
 * Copyright (c) 2023, Raul A Perusquia Flores
 */

import { defineStore } from "pinia";
import { notify } from "@kyvg/vue3-notification";

export const useEchoCustomerGeneral = defineStore("echo-customer-general", {
    state: () => ({
        // prospectsDashboard: {},
    }),
    actions: {
        subscribe() {
            window.Echo.private("customer.general").listen(".notification", (e) => {
                console.log("subscribe..");
                notify({
                    title: e.data.title,
                    text: e.data.text,
                    type: "info",
                });
            });
        },
    },
});
