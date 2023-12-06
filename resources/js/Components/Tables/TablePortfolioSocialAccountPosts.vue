<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 20 Mar 2023 23:18:59 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
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
import {faMicrophoneStand} from '@fal'
import {useFormatTime} from "@/Composables/useFormatTime";
import {Link} from "@inertiajs/vue3";

library.add(faFacebook, faTwitter, faTiktok, faPinterest, faLinkedin, faInstagram, faYoutube, faMicrophoneStand)

const props = defineProps<{
    data: object
    tab?: string
}>()

function postRoute(post) {
    switch (route().current()) {
        case 'customer.portfolio.social-accounts.show':
            return route(
                'customer.portfolio.social-accounts.post.show',
                [route().params.portfolioSocialAccount, post.slug]);
    }
}

</script>

<template>
    <Table :resource="data" :name="'post'" class="mt-5">
        <template #cell(task_name)="{ item: post }">
            <Link :href="postRoute(post)" :id="post['slug']" class="py-2 px-1">
                {{ post['task_name'] }}
            </Link>
        </template>
        <template #cell(platform)="{ item: post }">
            <div class="text-gray-500">
                <Icon class="ml-1" :data="post['platform']"/>
            </div>
        </template>
        <template #cell(start_at)="{ item: post }">
            <div class="text-gray-500">
                {{ useFormatTime(post['start_at']) }}
            </div>
        </template>
    </Table>
</template>
