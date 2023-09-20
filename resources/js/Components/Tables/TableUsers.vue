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
import Image from "@/Components/Image.vue";
import Tag from "@/Components/Tag.vue"


const props = defineProps<{
    data: object,
    tab: string,
}>()


function userRoute(user: User) {
    console.log(route().current())
    switch (route().current()) {
        case 'org.crm.customers.show.web-users.index':
            return route(
                'org.crm.customers.show.web-users.show',
                [route().params['customer'],user.username]);
        case 'sysadmin.users.index':
            return route(
                'sysadmin.users.show',
                [user.username]);
    }
}

function setColor(status: status) {
    switch (status) {
        case 'Active':
            return '#87d068';
        case 'Suspended':
            return '#ff5500';
    }
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(username)="{ item: user }">
                <Link :href="userRoute(user)" class="w-full h-full py-2" :id="user['username'].replace(' ','-')">
                    <template v-if="user['username']">{{ user['username'] }}</template>
                    <span v-else class="italic">{{ trans('Not set') }}</span>
                </Link>
        </template>

        <template #cell(avatar)="{ item: user }">
            <div class="flex justify-center">
                <Image :src="user['avatar']" class="w-6 aspect-square rounded-full" :alt="user.contact_name"/>
            </div>
        </template>

        <template #cell(status)="{ item: user }">
            <Tag :color="setColor(user['status'])">{{ user.status }}</Tag>
        </template>
    </Table>
</template>


