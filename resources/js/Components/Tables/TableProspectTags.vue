<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import { useLocaleStore } from "@/Stores/locale"
import {Link} from '@inertiajs/vue3'
import { faEnvelope, faAsterisk } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faEnvelope, faAsterisk)

const props = defineProps<{
    data: object
    tab?: string
}>()

const locale = useLocaleStore()

function tagRoute(tag: object)
{
    switch (route().current()) {
        case 'org.crm.shop.prospects.tags.index':
            return route(
                'org.crm.shop.prospects.tags.show',
                [route().params.shop, tag.tag_slug]);
        default:
            return route(
                'org.crm.tags.show',
                [tag.tag_slug]);
    }
}

</script>

<template>

    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(label)="{ item: tag }">
            <Link :href="tagRoute(tag)">
                {{ tag["label"]}}
            </Link>
        </template>
    </Table>
</template>


