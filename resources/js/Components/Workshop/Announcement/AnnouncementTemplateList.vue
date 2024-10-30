<script setup lang="ts">
import { ref, onMounted, inject, toRaw, isProxy } from 'vue';
import { faPresentation, faCube, faText, faImage, faImages, faPaperclip, faShoppingBasket, faStar, faHandHoldingBox, faBoxFull, faBars, faBorderAll, faLocationArrow} from "@fal"
import { library } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { trans } from "laravel-vue-i18n"

// import { Root, Daum } from "@/types/webBlockTypes"
// import AnnouncementInformation1 from '@/Components/Workshop/Announcement/Templates/Information/AnnouncementInformation1.vue'
// import AnnouncementPromo1 from '@/Components/Workshop/Announcement/Templates/Promo/AnnouncementPromo1.vue'
import axios from 'axios'
import { notify } from '@kyvg/vue3-notification'
import Image from '@/Components/Image.vue'
import { getAnnouncementComponent } from '@/Composables/useAnnouncement'
import Button from '@/Components/Elements/Buttons/Button.vue'
import { AnnouncementData } from '@/types/Announcement'
import Checkbox from 'primevue/checkbox'



library.add(faPresentation, faCube, faText, faImage, faImages, faPaperclip, faShoppingBasket, faStar, faHandHoldingBox, faBoxFull, faBars, faBorderAll, faLocationArrow)
const props = withDefaults(defineProps<{
    // onPickBlock: Function
    // webBlockTypes: Root
    // scope?: String /* all|website|webpage */
}>(), {
    scope: "all",
})

const announcementData = inject<AnnouncementData | null>('announcementData', null)
console.log('qqq', announcementData)

const announcements_list = ref<AnnouncementData[] | null>(null)
const currentTopbarCode = ref(null)
const selectedTemplate = ref(announcementData || null)
const isSelectFullTemplate = ref(false)

// const fake_templates = [
//     {
//         "name": "Information 1",
//         "code": "announcement-information-1",
//         "source": 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTQ0dFe77K2Mbmiz_7_nq5b9tL_HSnRfh4fbg&s',
//         "description": "This is information 1",
//         "category": "information",
//                 component: AnnouncementInformation1
//     },
//     {
//         "name": "Promo 1",
//         "code": "announcement-promo-1",
//         "description": "This is Promo 1",
//         "source": 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR_jQLYDfXfErKtmy_JLVAyIYfo_Xg8WejCkA&s',
//         "category": "promo",
//         component: AnnouncementPromo1
//     }
// ]


function mergeData(data1: {}, data2: {}) {
    // const data1a = toRaw(data1)
    // const data2a = toRaw(data2)

    // console.log('data1', data1)

    // for (const key in data2a) {
    //     console.log('keey', key)
    //     if (data1a.hasOwnProperty(key)) {
    //         if (data2a[key].text) {
    //             data1a[key].text = data2a[key].text;
    //         }
    //     } else {
    //         data1a[key] = data2a[key];
    //     }
    // }

    for (const key in data2) {
        // console.log('keey', key)
        if (data1.hasOwnProperty(key)) {
            // Only replace properties other than .text
            for (const prop in data2[key]) {
                if (prop !== 'text') {
                    data1[key][prop] = data2[key][prop];
                }
            }
        } else {
            // If key doesn't exist in data1, add it entirely
            data1[key] = data2[key];
        }
    }

    // console.log('data 111', data1)
    // return data1a;
}

const onSubmitTemplate = (template) => {
    // console.log('template', template)
    // console.log('core announce data', isProxy(announcementData))
    if(isSelectFullTemplate.value) {
        announcementData.code = template.code
        announcementData.fields = template.fields
        announcementData.container_properties = template?.container_properties
    } else {
        announcementData.code = template.code
    
        // mergeData(announcementData.container_properties, template.container)
        announcementData.container_properties = template.container_properties
    
        mergeData(announcementData.fields, template.fields)
    }


    // console.log('onSelectTempalte', announcementData)
}

// Method: fetch announcement list
const fetchAnnouncementList = async () => {
    try {
        const response = await axios.get(
            route('customer.banners.announcements.templates.index'),
        )

        console.log('respo', response.data)
        announcements_list.value = response.data.data
    } catch (error) {
        console.log(error)
        notify({
            title: trans("Something went wrong."),
            text: trans("Failed to fetch announcement templates."),
            type: "error"
        });
        // loadingState.value = false
    }
}

onMounted(() => {
    fetchAnnouncementList()
})


</script>

<template>

    <div class="flex justify-between items-center mb-2 border-b border-gray-200 pb-2">
        <div class="text-2xl font-medium">
            Announcement Templates
        </div>
        <div>
            <Button @click="() => onSubmitTemplate(selectedTemplate)" label="Submit" :disabled="!selectedTemplate" />
        </div>
    </div>

    <div class="h-full flex gap-x-8 border rounded-xl overflow-hidden">
        <nav class="w-1/5 bg-gray-100 py-4" aria-label="Sidebar">
            <ul role="list" class="space-y-1">
            
                <li v-for="item in ['promo', 'information']"
                    :key="item.id"
                    :class="[item.id === true ? 'bg-white text-indigo-600' : 'hover:bg-white/50 hover:text-indigo-600']"
                    @click="false"
                    class="group flex items-center gap-x-2 p-3 text-sm font-semibold cursor-pointer"
                >
                    <FontAwesomeIcon v-if="item.icon" :icon='item.icon' class='text-sm text-gray-400' fixed-width aria-hidden='true' />
                    {{ item }}
                </li>
            </ul>
        </nav>


        <section class="h-full mx-auto w-full pr-8 overflow-y-auto py-4">
            <div class="relative grid gap-y-8 gap-x-4 overflow-y-auto overflow-x-hidden">
                <div v-for="announcement in announcements_list"
                    :key="announcement.code"
                    class="cursor-pointer overflow-hidden h-fit group flex flex-col gap-x-2 relative isolate"
                    
                >
                    <div class="mb-1 w-fit"
                        :class="announcement.code === currentTopbarCode ? 'text-indigo-500 font-semibold shadow-xl' : 'bg-white'"
                    >
                        <div v-if="announcement.icon" class="flex items-center justify-center">
                            <FontAwesomeIcon :icon='announcement.icon' class='' fixed-width aria-hidden='true' />
                        </div>

                        <h3 class="text-sm">
                            {{ announcement.code }} <span v-if="announcement.code === announcementData?.code">(active)</span>
                        </h3>
                    </div>

                    <div
                        class="group relative min-h-16 max-h-20 w-full aspect-[4/1] overflow-hidden flex items-center bg-gray-100 justify-center rounded cursor-pointer"
                        :class="[
                            announcement.code == selectedTemplate?.code ? 'border border-indigo-500' : 'border border-gray-300 hover:border-indigo-500'
                        ]"
                    >
                        <div class="w-auto shadow-md">
                            <Image :src="announcement.source" class="object-contain"/>
                        </div>

                        <!-- Checkbox: Full template -->
                        <div @click="() => isSelectFullTemplate = !isSelectFullTemplate"
                            class="z-40 text-gray-400 hover:text-gray-700 items-center gap-x-3 absolute top-1.5 right-3"
                            :class="isSelectFullTemplate ? 'flex' : 'hidden group-hover:flex'"
                        >
                            <Checkbox :modelValue="isSelectFullTemplate" inputId="selectFullTempalte" name="selectFullTempalte" binary />
                            <span class="cursor-pointer select-none">{{ trans('Full template') }} </span>
                        </div>
                    </div>

                    <!-- Component: clickable -->
                    <component
                        :is="getAnnouncementComponent(announcement.code)"
                        isToSelectOnly
                        @templateClicked="(dataTemplate: {}) => (selectedTemplate = dataTemplate)"
                        class="z-30"
                    />

                    
                </div>
            </div>
        </section>


        <!-- <div class="flex-1 p-4">
            <section aria-labelledby="products-heading" class="h-full mx-auto w-full sm:px-6 lg:px-8">
                <TransitionGroup tag="div" name="zzz"
                    class="relative grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-y-3 gap-x-4">
                    <template v-if="announcements_list?.length">
                        <div
                            v-for="(announ, idxAnnoun) in announcements_list"
                            :key="idxAnnoun"
                            @click="() => false"
                            class="isolate relative min-h-10 h-20 max-h-24 min-w-20 w-auto border rounded cursor-pointer transition-all"
                            :class="[
                                announ.code == announcementData.code ? 'bg-indigo-500' : 'hover:bg-gray-100'
                            ]"
                        >
                            <Image :src="announ.source" />
                            
                            <component
                                :is="getAnnouncementComponent(announ.code)"
                                isToSelectOnly
                                @templateClicked="(dataTemplate) => onSubmitTemplate(dataTemplate, announ.code)"
                                class="z-50"
                            />

                            <div class="flex items-end absolute h-1/2 bottom-0 bg-gradient-to-t from-black/60 via-black/30 to-transparent w-full truncate text-xs pl-1 pb-1 text-white">
                                {{ announ.code }}
                            </div>
                        </div>
                    </template>

                    <div v-else class="text-center col-span-2 md:col-span-3 lg:col-span-4 text-gray-400">
                        {{ trans('No block in this category') }}
                    </div>
                </TransitionGroup>
            </section>
        </div> -->
    </div>
</template>

