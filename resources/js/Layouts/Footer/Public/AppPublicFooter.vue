
<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 04 Sep 2023 10:59:25 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, Ref } from 'vue'
import FooterTab from '@/Components/Footer/FooterTab.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {useLocaleStore} from "@/Stores/locale";
import Image from "@/Components/Image.vue";
import {usePage} from "@inertiajs/vue3";

const locale = useLocaleStore()

const isTabActive: Ref<boolean | string> = ref(false)
const logoSrc=usePage().props.art.footer_logo;

</script>

<template>
    <footer class="z-20 fixed w-screen bottom-0 right-0  text-white bg-black">
        <!-- Helper: Outer background (close popup purpose) -->
        <div class="fixed z-40 right-0 top-0 bg-transparent w-screen h-screen" @click="isTabActive = !isTabActive"
            :class="[isTabActive ? '' : 'hidden']"></div>
        <div class="flex justify-between">
            <!-- Left Section -->
            <div class="pl-4 flex items-center gap-x-1.5 py-1">
                <Image class="h-4 select-none"  :src="logoSrc" alt="Wowsbar" />

            </div>

            <!-- Tab Section -->
            <div class="flex items-end flex-row-reverse text-sm">


                <!-- Tab: Language -->
                <div class="relative h-full flex z-50 select-none justify-center items-center px-8 cursor-pointer text-gray-300"
                    :class="[isTabActive == 'language' ? 'bg-gray-700' : ''] "
                    @click="isTabActive = 'language'"
                >
                    <FontAwesomeIcon icon="fal fa-language" class="text-xs mr-1 h-5 " />
                    <div class="h-full font-extralight text-xs flex items-center leading-none"
                    >{{ locale.language.code }}</div>
                    <FooterTab @pinTab="() => isTabActive = false" v-if="isTabActive === 'language'" :tabName="`language`">
                        <template #default>
                            <div v-for="(option, index) in locale.languageOptions" :class="[ locale.language.id == index ? 'bg-gray-400 text-gray-100' : 'text-gray-100 hover:bg-gray-500', 'grid py-1.5']"
                                @click="locale.language.id = Number(index), locale.language.name = option.label"
                            >
                                {{ option.label }}
                            </div>
                        </template>
                    </FooterTab>
                </div>
            </div>

        </div>
    </footer>
</template>



