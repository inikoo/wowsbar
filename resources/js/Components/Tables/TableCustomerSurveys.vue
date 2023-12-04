<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 19:12:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { useLocaleStore } from "@/Stores/locale"
import { Link } from '@inertiajs/vue3'
import { faEnvelope, faAsterisk } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
library.add(faEnvelope, faAsterisk)

const props = defineProps<{
    data: object
    tab?: string
    create_mailshot:object
}>()

const locale = useLocaleStore()

function surveyRoute(survey: object) {
    switch (route().current()) {
        case 'org.crm.shop.customers.surveys.index':
            return route(
                'org.crm.shop.customers.surveys.show',
                [route().params.shop, survey.slug]);
        default:
            return route(
                'org.crm.surveys.show',
                [survey.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(name)="{ item: survey }">
            <Link :href="surveyRoute(survey)">
                {{ survey["name"] }}
            </Link>
        </template>
    </Table>
</template>
