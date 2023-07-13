<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {User} from "@/types/user";
import {trans} from "laravel-vue-i18n";


const props = defineProps<{
    data: object,
    tab: string,
}>()


function userRoute(user: User) {
    switch (route().current()) {
        case 'sysadmin.users.index':
            return route(
                'sysadmin.users.show',
                [user.username]);
    }
}


</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(username)="{ item: user }">
            <Link :href="userRoute(user)">
                <template v-if="user['username']">{{ user['username'] }}</template>
                <span v-else class="italic">{{ trans('Not set') }}</span>
            </Link>
        </template>
    </Table>
</template>


