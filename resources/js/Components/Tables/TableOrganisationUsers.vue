<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Wed, 04 Oct 2023 10:56:00 Malaysia Time, Office, Bali, Indonesia
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

    switch (route().current()) {
        case 'org.sysadmin.users.index':
            return route(
                'org.sysadmin.users.show',
                user.username);

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
            <Link :href="userRoute(user)" class="w-full h-full py-1 specialUnderlineOrg" :id="user['username'].replace(' ','-')">
                <template v-if="user['username']">{{ user['username'] }}</template>
            </Link>
        </template>

        <template #cell(avatar)="{ item: user }">
            <div class="flex justify-center">
                <Image :src="user['avatar']" class="w-6 aspect-square rounded-full" :alt="user.username"/>
            </div>
        </template>

        <!-- <template #cell(status)="{ item: user }">
            <Tag :color="setColor(user['status'])">{{ user['status'] }}</Tag>
        </template> -->

        <template #cell(roles)="{ item: user }">
            <div class="space-y-1">
                <Tag v-for="abcde in user.roles.split(',')" stringToColor :label="abcde">{{ abcde }}</Tag>
            </div>
        </template>
    </Table>
</template>


