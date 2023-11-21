<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
import { useLocaleStore } from "@/Stores/locale"
import Button from '@/Components/Elements/Buttons/Button.vue'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEnvelope, faAsterisk } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import PureInput from '@/Components/Pure/PureInput.vue'
import {Link} from "@inertiajs/vue3";
library.add(faEnvelope, faAsterisk)

const props = defineProps<{
    data: object
    tab?: string
}>()

const locale = useLocaleStore()

function listRoute(prospect) {
    switch (route().current()) {
        case 'org.crm.shop.prospects.mailshots.show':
            return route(
                'org.crm.shop.prospects.mailshots.recipients.show',
                [route().params['shop'], route().params['mailshot'], prospect.slug]);
        default:
            return route(
                'org.crm.lists.show',
                [prospect.slug]);
    }
}

</script>

<template>
    <!-- <pre>{{ usePage().props }}</pre> -->
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(contact_name)="{ item: prospect }">
            <Link :href="listRoute(prospect)">
                {{ prospect['contact_name'] }}
            </Link>
        </template>
    </Table>
</template>


