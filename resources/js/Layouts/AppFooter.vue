<script setup lang="ts">
import { ref, Ref } from 'vue'
import FooterTab from '@/Components/Footer/FooterTab.vue'
import { useLocaleStore } from "@/Stores/locale"
import { useLayoutStore } from "@/Stores/layout"
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

import { useDatabaseList, useDatabaseObject } from "vuefire"
import { getDatabase, ref as dbRef } from "firebase/database"
import { initializeApp } from "firebase/app"
import serviceAccount from "@/../private/firebase/wowsbar-firebase.json"

const locale = useLocaleStore()
const layout = useLayoutStore()
const firebaseApp = initializeApp(serviceAccount);
const db = getDatabase(firebaseApp)
const activities = useDatabaseList(dbRef(db, layout.tenant.code))

const isTabActive: Ref<boolean | string> = ref(false)

</script>

<template>
    <footer class="z-20 fixed w-screen bottom-0 right-0  text-white bg-black">
        <!-- Helper: Outer background (close popup purpose) -->
        <div class="fixed z-40 right-0 top-0 bg-transparent w-screen h-screen" @click="isTabActive = !isTabActive"
            :class="[isTabActive ? '' : 'hidden']"></div>
        <div class="flex justify-between">
            <!-- Left Section -->
            <div class="pl-4 flex items-center gap-x-1.5 py-1">
                <img src="@/../art/logo/png/2.png" alt="Wowsbar" class="h-4">
            </div>

            <!-- Tab Section -->
            <div class="flex items-end flex-row-reverse text-sm">
                <!-- Tab: Active Users -->
                <div class="relative h-full flex z-50 select-none justify-center items-center px-8 gap-x-1 cursor-pointer text-gray-300"
                    :class="[isTabActive == 'activeUsers' ? 'bg-gray-700' : '']"
                    @click="isTabActive == 'activeUsers' ? isTabActive = !isTabActive : isTabActive = 'activeUsers'"
                >
                    <div class="relative text-xs flex items-center gap-x-1">
                        <div class="animate-pulse ring-1 h-2 aspect-square rounded-full" :class="[activities.length > 0 ? 'bg-green-400 ring-green-600' : 'bg-gray-400 ring-gray-600']" />
                        <span class="">Active Users ({{ activities.length }})</span>
                    </div>

                    <FooterTab @pinTab="() => isTabActive = false" v-if="isTabActive == 'activeUsers'" :tabName="`activeUsers`">
                        <template #default>
                            <div v-for="(option, index) in activities" class="flex justify-start py-1 px-2 gap-x-1.5 hover:bg-gray-700 cursor-default">
                                <img :src="`/media/${option.user.avatar_id}`" :alt="option.user.contact_name" srcset="" class="h-4 rounded-full shadow">
                                <p class="text-left text-gray-100">
                                    <!-- <span class="font-semibold">{{ option.user.contact_name }}</span>  -->
                                    <span class="font-semibold text-gray-100">{{ option.user.username }}</span> -
                                    <span class="capitalize text-gray-300">{{ option.route.module }}</span>
                                </p>
                            </div>
                        </template>
                    </FooterTab>
                </div>

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



