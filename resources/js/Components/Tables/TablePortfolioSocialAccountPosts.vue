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
import { faMicrophoneStand } from '@fal/'
import {useFormatTime} from "@/Composables/useFormatTime";

library.add(faFacebook, faTwitter, faTiktok, faPinterest, faLinkedin, faInstagram, faYoutube, faMicrophoneStand)

const props = defineProps<{
    data: object
    tab?: string
}>()


</script>

<template>
    <Table :resource="data" :name="'post'" class="mt-5">
        <template #cell(platform)="{ item: account }">
            <div class="text-gray-500">
                <Icon class="ml-1" :data="account['platform']"/>
            </div>
        </template>
        <template #cell(start_at)="{ item: account }">
            <div class="text-gray-500">
                {{ useFormatTime(account['start_at']) }}
            </div>
        </template>
    </Table>
</template>
