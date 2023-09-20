<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 19 Sep 2023 15:25:16 Malaysia Time, Pantai Lembeng, Bali, Indonesia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, Ref } from 'vue'
import FooterTabActiveUsers from '@/Layouts/Customer/FooterActiveUsers.vue'
import FooterLanguage from '@/Components/Footer/FooterLanguage.vue'
import {usePage} from "@inertiajs/vue3";
import Image from "@/Components/Image.vue";
import {faHeart,faComputerClassic} from "../../../private/pro-solid-svg-icons"
import {library} from "@fortawesome/fontawesome-svg-core";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const isTabActive: Ref<boolean | string> = ref(false)
const logoSrc=usePage().props.art.footer_logo;

library.add(faHeart,faComputerClassic)

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
                <Image class="h-4 select-none"  :src="logoSrc" alt="T-Wowsbar" />
                <span class="text-slate-400	 text-xs">
                    Made with
                    <FontAwesomeIcon icon='fas fa-heart' class="text-red-500 mx-1" aria-hidden='true'/>
                    and
                    <FontAwesomeIcon icon='fas fa-computer-classic' class="mx-1" aria-hidden='true'/>
                    in Bali
                </span>
            </div>


            <!-- Right: Tab Section -->
            <div class="flex items-end flex-row-reverse text-sm">
                <FooterTabActiveUsers :isTabActive="isTabActive" @isTabActive="(value: any) => isTabActive = value" />
                <FooterLanguage :isTabActive="isTabActive" @isTabActive="(value: any) => isTabActive = value" />
            </div>
        </div>
    </footer>
</template>



