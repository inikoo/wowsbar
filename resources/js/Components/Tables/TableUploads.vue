<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import Table from '@/Components/Table/Table.vue'
import { useFormatTime } from "@/Composables/useFormatTime"
import { useLocaleStore } from "@/Stores/locale"
import {User} from "@/types/user";
import {Link} from '@inertiajs/vue3'

const props = defineProps<{
    data: {
        data: {
            id: number
            number_rows: number
            number_success: number
            number_fails: number
            original_filename: string
            uploaded_at: string
        }[]
    }
    tab?: string
}>()

const locale = useLocaleStore()

function uploadRoute(upload) {
    switch (route().current()) {
        case 'org.crm.shop.prospects.uploads.index':
            return route(
                'org.crm.shop.prospects.uploads.show',
                [route().params['shop'], upload.id]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(original_filename)="{ item }">
            <Link :href="uploadRoute(item)" class="w-full h-full py-2">
                {{ item.original_filename }}
            </Link>
        </template>
        <template #cell(updated_at)="{ item }">
            <span class="text-gray-500">
                {{ useFormatTime(item.uploaded_at, { localeCode: locale.language.code, formatTime: 'hms' }) }}
            </span>
        </template>
    </Table>
</template>
