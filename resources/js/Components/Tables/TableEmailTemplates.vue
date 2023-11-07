<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 27 Oct 2023 10:09:11 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';

const props = defineProps<{
    data: object,
    tab?: string
}>()

function emailTemplateRoute(emailTemplate) {
    switch (route().current()) {
        case 'org.crm.shop.mailroom.dashboard':
            return route(
                'org.crm.shop.mailroom.templates.show',
                [emailTemplate.scope.slug, emailTemplate.slug]);
        default:
            return route(
                'org.crm.mailroom.templates.show',
                [emailTemplate.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(title)="{ item: emailTemplate }">
            <Link :href="emailTemplateRoute(emailTemplate)">
                {{ emailTemplate['title'] }}
            </Link>
        </template>
    </Table>
</template>


