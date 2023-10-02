<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 29 Sep 2023 20:16:51 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Link} from '@inertiajs/vue3';
import Table from '@/Components/Table/Table.vue';
import {User} from "@/types/user";
import Image from "@/Components/Image.vue";
import Tag from "@/Components/Tag.vue"


const props = defineProps<{
    data: object,
    tab: string,
}>()


function userRoute(user: User) {
    return route(
        'customer.sysadmin.users.show',
        [user.slug]);
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
        <template #cell(slug)="{ item: user }">
                <Link :href="userRoute(user)" class="w-full h-full py-2" :id="user['slug']">
                    {{ user['slug'] }}
                </Link>
        </template>

        <template #cell(avatar)="{ item: user }">
            <div class="flex justify-center">
                <Image :src="user['avatar']" class="w-6 aspect-square rounded-full" :alt="user.contact_name"/>
            </div>
        </template>

        <template #cell(status)="{ item: user }">
            <Tag :color="setColor(user['status'])">{{ user['status'] }}</Tag>
        </template>
    </Table>
</template>


