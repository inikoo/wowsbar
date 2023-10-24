<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import Table from '@/Components/Table/Table.vue'
import Icon from "@/Components/Icon.vue";
import {library} from "@fortawesome/fontawesome-svg-core";
import {
    faFacebook,
    faInstagram,
    faLinkedin,
    faPinterest,
    faTiktok,
    faTwitter, faYoutube
} from "@fortawesome/free-brands-svg-icons";
import { faMicrophoneStand } from '@fal/'

library.add(faFacebook, faTwitter, faTiktok, faPinterest, faLinkedin, faInstagram, faYoutube, faMicrophoneStand)

const props = defineProps<{
    data: object
    tab?: string
}>()


function accountRoute(account) {
    switch (route().current()) {
        case 'customer.portfolio.social-accounts.index':
            return route(
                'customer.portfolio.social-accounts.show',
                [account.slug]);
        case 'org.crm.shop.customers.show':
            return route(
                'org.crm.shop.customers.show.customer-social-accounts.show',
                [route().params.shop, route().params.customer, account.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="'social_account'" class="mt-5">
        <template #cell(platform)="{ item: account }">
            <div class="text-gray-500">
                <Icon class="ml-1" :data="account['platform_icon']"/>
            </div>
        </template>
        <template #cell(username)="{ item: account }">
            <Link :href="accountRoute(account)" :id="account['slug']" class="py-2 px-1">
                {{ account['username'] }}
            </Link>
        </template>
    </Table>

</template>
