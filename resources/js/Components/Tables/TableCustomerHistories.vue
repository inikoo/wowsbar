<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 13 Oct 2023 09:38:55 Malaysia Time, Office, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
//import JsonViewer from 'vue-json-viewer'
import { useFormatTime } from '@/Composables/useFormatTime'
import { useLocaleStore } from '@/Stores/locale'
const locale = useLocaleStore()
import {trans} from "laravel-vue-i18n";

const props = defineProps<{
    data: object,
    tab?: string
}>()

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(customer_user_slug)="{ item: history }">
            {{history['customer_user_slug']??trans('Command line') }}
        </template>
        <template #cell(created_at)="{ item: history }">
            {{ useFormatTime(history['created_at'], { localeCode: locale.language.code, formatTime: 'hms' }) }}
        </template>
        <template #cell(action)="{ item: history }">
            {{history['natural_language']}}
        </template>
    </Table>
</template>
