<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Fri, 29 Sep 2023 20:16:51 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import { User } from "@/types/user"
import Image from "@/Components/Image.vue"
import Tag from "@/Components/Tag.vue"


const props = defineProps<{
    data: {}
    tab: string
}>()


function userRoute(user: User) {
    return route(
        'customer.sysadmin.users.show',
        [user.slug]);
}

const setThemeStatus = (status: string) => {
    switch (status) {
        case 'Active':
            return 1;
        case 'Suspended':
            return 2;

        default:
            return 3
    }
}

const setThemeRoles = (roles: string) => {
    switch (roles) {
        case 'super-admin':
            return 1;
        case 'portfolio':
            return 2;
        case 'leads':
            return 3;
        case 'seo':
            return 4;
        case 'ppc':
            return 5;
        case 'prospects':
            return 6;
        case 'social':
            return 7;
        case 'banners':
            return 8;

        default:
            return 9
    }
}

// To split
const splitRoles = (word: string) => {
    return word.split(', ')
}

</script>

<template>
    <Table :resource="data" :name="tab" class="mt-5">
        <template #cell(slug)="{ item: user }">
            <Link :href="userRoute(user)" class="w-full h-full py-2" :id="user.slug">
                {{ user.slug }}
            </Link>
        </template>

        <template #cell(avatar)="{ item: user }">
            <div class="flex justify-center" :title="user.contact_name">
                <Image :src="user.avatar" class="w-6 aspect-square rounded-full overflow-hidden shadow ring-1 ring-gray-100" :alt="user.contact_name" />
            </div>
        </template>

        <template #cell(status)="{ item: user }">
            <Tag :theme="setThemeStatus(user.status)" :key="user.id">{{ user.status }}</Tag>
        </template>

        <template #cell(roles)="{ item: user }">
            <div class="flex gap-x-1 flex-wrap gap-y-1">
                <Tag v-for="role in splitRoles(user.roles)" :theme="setThemeRoles(role)" :key="user.id">
                    {{ role }}
                </Tag>
            </div>
        </template>
    </Table>
</template>


