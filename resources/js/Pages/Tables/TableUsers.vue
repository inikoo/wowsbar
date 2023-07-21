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
            <div class="flex">
                <Link :href="userRoute(user)" class="w-full h-full py-2">
                    <template v-if="user['username']">{{ user['username'] }}</template>
                    <span v-else class="italic">{{ trans('Not set') }}</span>
                </Link>
            </div>
        </template>

        <template #cell(avatar)="{ item: user }">
            <!-- {{ user  }} -->
            <div class="flex justify-center">
                <img :src="`/media/${user['avatar']}`" class="w-6 aspect-square rounded-full" :alt="user.contact_name"/>
            </div>
        </template>
    </Table>
</template>


