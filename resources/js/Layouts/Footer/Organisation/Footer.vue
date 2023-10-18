<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 04 Sep 2023 10:37:14 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, Ref } from 'vue'
import FooterTabActiveUsers from '@/Layouts/Organisation/FooterActiveUsers.vue'
import FooterLanguage from '@/Components/Footer/FooterLanguage.vue'
import {usePage} from "@inertiajs/vue3";
import Image from "@/Components/Image.vue";
import { faDiscord } from '@fortawesome/free-brands-svg-icons'
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

library.add(faDiscord)

const isTabActive: Ref<boolean | string> = ref(false)
const logoSrc=usePage().props.art.footer_logo;

</script>

<template>
    <footer class="z-20 fixed w-screen bottom-0 right-0  text-white bg-black">
        <!-- Helper: Outer background (close popup purpose) -->
        <div class="fixed z-40 right-0 top-0 bg-transparent w-screen h-screen"
            @click="isTabActive = !isTabActive"
            :class="[isTabActive ? '' : 'hidden']" />

        <div class="flex justify-between">
            <!-- Left: Logo Section -->
            <div class="pl-4 flex items-center gap-x-1.5 py-1">
                <Image class="h-auto w-14 select-none" :src="logoSrc" alt="Org-Wowsbar" />
            </div>

            <div class="pl-4 flex items-center gap-x-1.5 py-1">
                <a href="https://discord.gg/C7bCmMaTxP" target="_blank">
                    <span class="text-slate-400	 text-xs">
                        <FontAwesomeIcon :icon="['fab', 'discord']" class="text-white mx-1" aria-hidden='true'/>
                        Give us feedback from your journey
                    </span>
                </a>
            </div>

            <!-- Right: Tab Section -->
            <div class="flex items-end flex-row-reverse text-sm">
                <FooterTabActiveUsers :isTabActive="isTabActive" @isTabActive="(value: any) => isTabActive = value"/>
                <FooterLanguage :isTabActive="isTabActive" @isTabActive="(value: any) => isTabActive = value" />
            </div>
        </div>
    </footer>
</template>



