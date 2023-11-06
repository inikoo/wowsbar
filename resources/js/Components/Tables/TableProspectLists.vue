<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import { useLocaleStore } from "@/Stores/locale"
import Button from '@/Components/Elements/Buttons/Button.vue'
import { useRangeFromNow } from '@/Composables/useFormatTime'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faEnvelope, faAsterisk } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faEnvelope, faAsterisk)

const props = defineProps<{
    data: object
    tab?: string
}>()
const locale = useLocaleStore()


const countDate = () => {
    const tempDate = new Date()
    return useRangeFromNow
}


</script>

<template>
    <!-- {{  countDate() }} -->
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(number_items)="{ item: prospect_list }">
            <span class="tabular-nums">{{ locale.number(prospect_list['number_items']) }}</span>
        </template>

        <template #cell(description)="{ item: prospect_list }">
            <!-- {{ prospect_list.constrains }}
            <br />================== -->
            <div class="flex items-center gap-x-2">
                <div v-if="prospect_list.constrains.with === 'email'" class="inline-flex items-start">
                    <FontAwesomeIcon icon='fal fa-asterisk' class='h-2 text-red-500' aria-hidden='true' />
                    <FontAwesomeIcon icon='fal fa-envelope' class='' aria-hidden='true' />
                </div>
                <p v-if="prospect_list.constrains.where?.[2]" class="text-gray-500">(Not contacted yet)</p>
                <p v-if="prospect_list.constrains?.group" class="text-gray-500 whitespace-nowrap">(Last contacted at: {{ prospect_list.arguments.__date__?.value?.quantity }} {{ prospect_list.arguments.__date__?.value?.unit }})</p>
            </div>
        </template>

        <template #cell(actions)="{ item: prospect_list }">
            <!-- {{ prospect_list }} -->
            <!-- org.crm.shop.prospects.show
            {
                "shop": "awa",
                "prospect": "075-3"
            } -->
            <Button :style="prospect_list.argument ? 'primary' : 'secondary'" label="1 Week" size="xs" />
        </template>
    </Table>
</template>


