<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">

import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {Webpage} from "@/types/webpage";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {
    faSignIn,faHome,faNewspaper,faBrowser,faUfoBeam
} from "../../../private/pro-light-svg-icons"
import {library} from "@fortawesome/fontawesome-svg-core";
library.add(
    faSignIn,faHome,faNewspaper,faBrowser,faUfoBeam
)
const props = defineProps<{
    data: object
    tab?: string
}>()


function webpageRoute(webpage: Webpage) {
    switch (route().current()) {
        case 'org.website.webpages.show':
        case 'org.website.webpages.index':
            return route(
                'org.website.webpages.show',
                [webpage.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(code)="{ item: webpage }">
            <Link :href="webpageRoute(webpage)">
                {{ webpage['code'] }}
            </Link>
        </template>
        <template #cell(type)="{ item: webpage }">
            <font-awesome-icon :icon="webpage.typeIcon" />
        </template>
        <template #cell(level)="{ item: webpage }">
          <div class="flex justify-start"> {{ webpage['level'] }}</div>
        </template>
    </Table>


</template>


