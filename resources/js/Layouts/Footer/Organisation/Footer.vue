<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 04 Sep 2023 10:37:14 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, Ref, computed } from 'vue';
import FooterTabActiveUsers from '@/Layouts/Organisation/FooterActiveUsers.vue'
import FooterLanguage from '@/Components/Footer/FooterLanguage.vue'
import { usePage } from "@inertiajs/vue3"
import Image from "@/Components/Image.vue"
import { faDiscord } from '@fortawesome/free-brands-svg-icons'
import { library } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { trans } from "laravel-vue-i18n"
import { useLayoutStore } from '@/Stores/layout'
import { useEchoOrgPersonal } from '@/Stores/echo-org-personal'
import ProgressLine from '@/Components/Utils/ProgressLine.vue'

library.add(faDiscord)

const isTabActive: Ref<boolean | string> = ref(false)

// Retrieve the latest upload progress
const compLatestProgress = computed(() => {
    if(!useEchoOrgPersonal().progressBars.Upload) return null
    const highestKey = Math.max(...(Object.keys(useEchoOrgPersonal().progressBars.Upload ?? [])))
    return useEchoOrgPersonal().progressBars.Upload[highestKey];
})

</script>

<template>
    <footer class="z-[19] fixed w-full bottom-0 right-0  text-gray-700 bg-org-30 border-t border-org-500/30">
        <!-- Helper: Outer background (close popup purpose) -->
        <div @click="isTabActive = !isTabActive"
            class="fixed z-40 right-0 top-0 bg-transparent w-screen h-screen"
            :class="[isTabActive ? '' : 'hidden']" />

        <div class="flex justify-between transition-all duration-200 ease-in-out"
            :class="[useLayoutStore().leftSidebar.show ? 'pl-52' : 'pl-12']"
        >
            <!-- Left: Logo Section -->
            <!-- <div class="pl-4 flex items-center gap-x-1.5 py-1">
                <Image class="h-auto w-14 select-none" :src="logoSrc" alt="Org-Wowsbar" />
            </div> -->

            <!-- Section: Discord -->
            <div class="flex items-center gap-x-1.5 py-1">
                <a href="https://discord.gg/C7bCmMaTxP" target="_blank" class="text-slate-600 text-xs">
                    <FontAwesomeIcon :icon="['fab', 'discord']" class="mx-1" aria-hidden='true' />
                    {{ trans("Customer's community channel") }}
                </a>
            </div>

            <!-- Right: Tab Section -->
            <div class="flex items-center flex-row-reverse text-sm">
                <FooterTabActiveUsers :isTabActive="isTabActive" @isTabActive="(value: any) => isTabActive = value" />
                <FooterLanguage :isTabActive="isTabActive" @isTabActive="(value: any) => isTabActive = value" />
                
                <!-- Progress Upload -->
                <Transition name="slide-to-up">
                    <div v-if="compLatestProgress" class="flex justify-center items-center gap-x-2 text-gray-600 mx-4">
                        <div class="text-xs leading-none">{{ `${compLatestProgress.action_type} ${compLatestProgress.data.type}` }}</div>
                        <ProgressLine :total="compLatestProgress.total" :success="compLatestProgress.data.number_success" :fails="compLatestProgress.data.number_fails" />
                    </div>
                </Transition>
            </div>
        </div>
    </footer>
</template>



