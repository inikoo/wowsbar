<script setup lang='ts'>
import { useLayoutStore } from '@/Stores/layout'
import { trans } from 'laravel-vue-i18n'
import Image from '@/Components/Image.vue'
import { useCopyText } from '@/Composables/useCopyText'

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCopy, faMale, faMapMarkerAlt, faPhone } from '@fal/'
import { library } from '@fortawesome/fontawesome-svg-core'
import Tag from '@/Components/Tag.vue';
library.add(faCopy, faMale, faMapMarkerAlt, faPhone)

</script>

    <!-- <div class="px-4 py-2">
        <div class="grid grid-cols-5 shadow rounded">
            <div class="bg-violet-400 col-span-1">
                VA Carito Lorem Ipsum
            </div>
            
            <div class="col-span-3">
                dddd
            </div>
            
            <div class="col-span-1">
                xxx
            </div>
        </div>
    </div> -->

<template>
    <div class="px-4 py-4">
        <div class="grid grid-cols-5 w-full gap-x-4">
            <div class="col-span-3 flex flex-col">
                <!-- Section: Information -->
                <div class="font-semibold text-xl text-slate-600">Information</div>
                <div class="bg-gradient-to-r from-org-600 to-org-500 rounded-lg shadow text-purple-100 py-4 px-6 space-y-2">
                    <div class="flex gap-x-1 w-fit py-1 rounded">
                        <Tag label="New Customer" stringToColor />
                        <Tag label="Rebrand" stringToColor />
                        <Tag label="Wholesale" stringToColor />
                    </div>

                    <!-- Company name -->
                    <div class="grid grid-flow-col justify-start items-center gap-x-4">
                        <FontAwesomeIcon fixed-width icon="fal fa-building" class="" aria-hidden="true" />
                        <div class="flex items-center leading-none">
                            AW Advantage
                            <div class="group cursor-pointer px-1.5 flex justify-center text-xl "
                                @click="useCopyText('Aw Advantage')">
                                <FontAwesomeIcon icon="fal fa-copy"
                                    class="text-sm leading-none opacity-40 group-hover:opacity-75 group-active:opacity-100"
                                    aria-hidden="true" />
                            </div>
                        </div>
                    </div>

                    <!-- Contact name -->
                    <div class="grid grid-flow-col justify-start items-center gap-x-4">
                        <FontAwesomeIcon fixed-width icon="fal fa-male" class="" aria-hidden="true" />
                        <span class="leading-none">info@advantage.com</span>
                    </div>

                    <!-- Email address -->
                    <div class="grid grid-flow-col justify-start items-center gap-x-4">
                        <FontAwesomeIcon fixed-width icon="fal fa-envelope" class="" aria-hidden="true" />
                        <div class="flex items-center leading-none">
                            <a :href="`mailto`" class="hover:text-indigo-500 leading-5">asdsadsa@gmail.com</a>
                            <div class="group cursor-pointer px-1.5 flex justify-center text-xl " @click="useCopyText('dsadsadsa')">
                                <FontAwesomeIcon icon="fal fa-copy"
                                    class="text-sm leading-none opacity-40 group-hover:opacity-75 group-active:opacity-100"
                                    aria-hidden="true" />
                            </div>
                        </div>
                    </div>

                    <!-- Telephone address -->
                    <div class="grid grid-flow-col justify-start items-center gap-x-4">
                        <FontAwesomeIcon fixed-width icon="fal fa-phone" class="" aria-hidden="true" />
                        <div class="flex items-center leading-none">
                            0852-3769-1990
                            <div class="group cursor-pointer px-1.5 flex justify-center text-xl " @click="useCopyText(132131056498)">
                                <FontAwesomeIcon icon="fal fa-copy"
                                    class="text-sm leading-none opacity-40 group-hover:opacity-75 group-active:opacity-100"
                                    aria-hidden="true" />
                            </div>
                        </div>
                    </div>

                    <!-- Location address -->
                    <div class="grid grid-flow-col justify-start items-center gap-x-4">
                        <FontAwesomeIcon fixed-width icon="fal fa-map-marker-alt" class="" aria-hidden="true" />
                        <p class="leading-5">
                            Jl. Pantai Lembeng No.5<br />
                            Ketewel, Kec. Sukawati
                            <span>Kabupaten Gianyar<br /></span>
                            <span>Bali 80582</span>
                            <span>, Indonesia</span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Section: Creator -->
            <div class="col-span-2 flex flex-col">
                <div class="font-semibold text-xl text-slate-600">Reference</div>
                <div class="bg-org-100 w-9/12 rounded-lg text-slate-700 py-2 px-4 flex flex-col gap-y-4 items-center justify-center shadow border border-gray-300 h-full">
                    <div class="text-center">
                        <div class="text-xs text-gray-400 italic">
                            Agents
                        </div>
                        <div class="text-xl leading-none font-semibold">
                            Zuzana Margitai
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-400 italic">
                            Created on
                        </div>
                        <div class="leading-none text-sm">
                            Wed, 12 Apr 2023 15:18
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="text-xs text-gray-400 italic">
                            Contacted on
                        </div>
                        <div class="leading-none text-sm">
                            Wed, 12 Apr 2023 17:30
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>