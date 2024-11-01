<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:20:36 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { inject, nextTick, onMounted, provide, reactive, ref, toRaw, watch } from 'vue'
import PageHeading from '@/Components/Headings/PageHeading.vue'
import { capitalize } from "@/Composables/capitalize"
import { trans } from 'laravel-vue-i18n'
// import Promo1 from '@/Components/Workshop/Announcement/Templates/Promo/AnnouncementPromo1.vue'
// import Information1 from '@/Components/Workshop/Announcement/Templates/Information/AnnouncementInformation1.vue'
import AnnouncementTemplateList from '@/Components/Workshop/Announcement/AnnouncementTemplateList.vue'


import { library } from "@fortawesome/fontawesome-svg-core"
import { faGlobe, faImage, faExternalLink, faRocketLaunch, faSave, faUndoAlt, faInfoCircle } from '@fal'
import { faThLarge } from '@fas'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import AnnouncementSideEditor from '@/Components/Workshop/Announcement/AnnouncementSideEditor.vue'
import { notify } from '@kyvg/vue3-notification'
import ScreenView from '@/Components/ScreenView.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import LoadingIcon from '@/Components/Utils/LoadingIcon.vue'
import { debounce, remove } from 'lodash'
import Modal from '@/Components/Utils/Modal.vue'
import { getAnnouncementComponent } from '@/Composables/useAnnouncement'
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import { routeType } from '@/types/route'
import EmptyState from '@/Components/Utils/EmptyState.vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import PureMultiselect from '@/Components/Pure/PureMultiselect.vue'
import Select from 'primevue/select'
import InputText from 'primevue/inputtext'
import InputGroup from 'primevue/inputgroup';
import InputGroupAddon from 'primevue/inputgroupaddon';
import Tag from '@/Components/Tag.vue'

library.add(faGlobe, faImage, faExternalLink, faRocketLaunch, faSave, faUndoAlt, faInfoCircle, faThLarge)

const props = defineProps<{
    pageHead: {}
    title: string
    data: {},
    firstBanner?: {
        text: string
        createRoute: {
            name: string
            parameters?: any[]
        }
    }
    announcement_data: {
        code: string
        id: number
        name: string
        ulid: string
        created_at: string
        updated_at: string
        icon: string
        close_button: {
            size: string
            text_color: string
            block_properties: {
                position: {
                    x: string
                    y: string
                    type: string  // 'absolute' | 'relative'
                }
            }
        }
    }
    announcement_list: {}
    routes_list: {
        publish_route: routeType
        update_route: routeType
        reset_route: routeType
    }
}>()

const isModalOpen = ref(false)
const isLoadingSave = ref(false)

const saveCancelToken = ref<Function | null>(null)
const onSave = () => {
    router.post(
        route(props.routes_list.update_route.name, props.routes_list.update_route.parameters),
        announcementData.value,
        {
            onStart: () => isLoadingSave.value = true,
            onFinish: () => {
                isLoadingSave.value = false
                saveCancelToken.value = null
            },
            onSuccess: () => {
                notify({
                    title: 'Success',
                    text: 'Data Announcement saved',
                    type: 'success',
                })
            },
            onError: (error) => {
                notify({
                    title: 'Something went wrong',
                    text: 'Failed to save Announcement data',
                    type: 'error',
                })
            },
            onCancelToken: (cclToken) => saveCancelToken.value = cclToken.cancel,
            preserveState: true,
            preserveScroll: true
        }
    )
}
const onPublish = () => {
    router.post(
        route(props.routes_list.publish_route.name, props.routes_list.publish_route.parameters),
        announcementData.value,
        {
            onStart: () => isLoadingSave.value = true,
            onFinish: () => {
                isLoadingSave.value = false
                saveCancelToken.value = null
            },
            onError: (error) => {
                notify({
                    title: 'Something went wrong',
                    text: error.message,
                    type: 'error',
                })
            },
            preserveState: true,
            preserveScroll: true
        }
    )
}
const onReset = () => {
    router.post(
        route(props.routes_list.reset_route.name, props.routes_list.reset_route.parameters),
        announcementData.value,
        {
            onStart: () => isLoadingSave.value = true,
            onFinish: () => {
                isLoadingSave.value = false
                saveCancelToken.value = null
            },
            onError: (error) => console.error('======', error),
            onCancelToken: (cclToken) => saveCancelToken.value = cclToken.cancel,
            preserveState: true,
            preserveScroll: true
        }
    )
}

const announcementData = ref(props.announcement_data)
provide('announcementData', announcementData.value)

// If fieldvalue have changes, then auto save
watch(announcementData, (newVal) => {
    if (newVal) {
        // If still on progress saving, cancel the save
        if (saveCancelToken.value) {
            saveCancelToken.value()
        }

        xxx()
    }
}, { deep: true })

const xxx = debounce(() => onSave(), 1000, { leading: false, trailing: true })


const isOnPublishState = inject('isOnPublishState', false)
const styleToRemove = isOnPublishState ? ['top'] : null
const _parentComponent = ref(null)

const isLoadingTab = ref<string | null>(null)
const selectedTab = ref('Setting')
const changeTab = async (category: string) => {
    isLoadingTab.value = category
    selectedTab.value = category
    await nextTick()
    isLoadingTab.value = null
}

const announcementSetting = ref({
    target: {
        type: 'all', // 'specific'
        specific: [
            {
                will: 'show', // 'hide'
                when: 'contain', // 'matches'
                url: 'blog/subpage'
            }
        ]
    }
})
const specificNew = ref({
    will: 'show', // 'hide'
    when: 'contain', // 'matches'
    url: ''
})
const addSpecificPage = () => {
    announcementSetting.value.target.specific.push(specificNew.value)

    specificNew.value = {
        will: 'show',
        when: 'contain',
        url: ''
    }
}
const onDeleteSpecific = (specIndex: number) => {
    remove(announcementSetting.value.target.specific, (item, index) => {
        return index == specIndex
    })

}
</script>

<template layout="CustomerApp">

    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #iconRight v-if="isLoadingSave">
            <LoadingIcon />
        </template>

        <template #other>
            <div class="flex gap-x-2">
                <Button @click="onReset" label="Reset" v-tooltip="'Reset data to last publish'" :loading="isLoadingSave"
                    :style="'negative'" icon="fal fa-undo-alt" />
                <Button @click="onSave" label="save" :loading="isLoadingSave" :style="'tertiary'" icon="fal fa-save" />
                <Button @click="onPublish" label="Publish" :loading="isLoadingSave" iconRight="fal fa-rocket-launch" />
            </div>
        </template>
    </PageHeading>

    <div class="mx-auto max-w-md px-2 sm:px-0 my-4">
        <!-- {{ selectedTab }} -->
        <TabGroup>
            <TabList class="flex space-x-1 rounded-xl bg-indigo-500 p-1">
                <Tab v-for="category in ['Workshop', 'Setting']" as="template" :key="category" v-slot="{ selected }"
                    @click="async () => changeTab(category)">
                    <button :class="[
                        'px-8 w-full rounded-lg py-2.5 text-sm font-medium leading-5 ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                        selected
                            ? 'bg-white text-blue-700 shadow'
                            : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
                        ]">
                        {{ category }}
                        <LoadingIcon v-if="isLoadingTab === category" />
                    </button>
                </Tab>
            </TabList>

        </TabGroup>
    </div>

    <!-- Section: Workshop -->
    <div v-if="selectedTab == 'Workshop'" class="flex h-[86.7vh] border-t border-gray-300">
        <!-- Section: Side editor -->
        <div class="w-[400px] py-2 px-3">
            <div class="w-full text-lg font-semibold flex items-center justify-between gap-3 border-b border-gray-300">
                <div class="flex items-center gap-3">
                    {{ trans('Announcement') }}
                </div>

                <div class="py-1 px-2 cursor-pointer" title="template" v-tooltip="'Template'"
                    @click="isModalOpen = true">
                    <FontAwesomeIcon icon="fas fa-th-large" aria-hidden='true' />
                </div>
            </div>

            <AnnouncementSideEditor v-if="announcementData.code" />
        </div>

        <!-- Section: Preview -->
        <div v-if="true" class="w-full h-full flex flex-col py-2 px-3">
            <div class="flex justify-between">
                <!-- <div class="py-1 px-2 cursor-pointer md:hidden block" title="Desktop view" v-tooltip="'Navigation'">
                    <FontAwesomeIcon :icon='faBars' aria-hidden='true' @click="()=>openDrawer = true" />
                    <Drawer v-model:visible="openDrawer" :header="''" :dismissable="true">
                        <WebpageSideEditor ref="_WebpageSideEditor" :webpage="data" :webBlockTypeCategories="webBlockTypeCategories"
                            @update="sendBlockUpdate" @delete="sendDeleteBlock" @add="addNewBlock"
                            @order="sendOrderBlock"  @openBlockList="()=>{openDrawer = false, _WebpageSideEditor.isModalBlocksList = true}" />
                    </Drawer>
                </div> -->

                <!-- Section: Screenview -->
                <div class="flex">
                    <ScreenView @screenView="false" />

                    <a :href="`http://delivery.wowsbar.test/announcement/${announcementData.ulid}?iframe=true`"
                        target="_blank" class="py-1 px-2 cursor-pointer" title="Desktop view" v-tooltip="'Preview'">
                        <FontAwesomeIcon icon='fal fa-external-link' aria-hidden='true' />
                    </a>
                </div>
            </div>

            <div class="border-2 h-full w-full">
                <div v-if="isIframeLoading" class="flex justify-center items-center w-full h-64 p-12 bg-white">
                    <FontAwesomeIcon icon="fad fa-spinner-third" class="animate-spin w-6" aria-hidden="true" />
                </div>

                <div class="h-full w-full bg-white relative">
                    <div v-if="announcementData.code" ref="_parentComponent" :style="{
                            ...propertiesToHTMLStyle(announcementData.container_properties, { toRemove: styleToRemove}),
                            position: announcementData.container_properties?.position?.type === 'fixed' ? 'absolute' : announcementData.container_properties?.position?.type
                        }">
                        <component :is="getAnnouncementComponent(announcementData.code)"
                            :announcementData="announcementData" :key="announcementData.code" isEditable
                            :_parentComponent />
                    </div>

                    <div v-else class="text-center">
                        <EmptyState :data="{ title: trans('No Announcement selected')}" />
                        <div class="mx-auto mt-4">
                            <Button @click="() => isModalOpen = true" :style="'tertiary'"
                                label="Select from template" />
                        </div>

                    </div>
                    <!-- <br>
                    <br>
                    <pre>{{ announcementData }}</pre> -->
                </div>
            </div>
        </div>
    </div>

    <!-- Section: Setting -->
    <div v-if="selectedTab == 'Setting'">
        <div class="max-w-4xl mx-auto px-4">
            <!-- Section: Target -->
            <fieldset class="mb-6">
                <legend class="text-xl font-semibold ">Target</legend>
                <p class="text-sm/6 text-gray-600">
                    Select where the Announcement will be displayed
                </p>
                <div class="mt-2">
                    <div class="flex items-center gap-x-3">
                        <input v-model="announcementSetting.target.type" value="all" id="push-everything" name="push-notifications" type="radio"
                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                        <label for="push-everything"
                            class="cursor-pointer block font-medium ">All pages</label>
                    </div>
                    <div class="flex items-center gap-x-3">
                        <input v-model="announcementSetting.target.type" value="specific" id="push-email" name="push-notifications" type="radio"
                            class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                        <label for="push-email" class="cursor-pointer block font-medium ">
                            Specific page
                        </label>

                    </div>

                    <!-- Section: Target specific -->
                    <div v-if="announcementSetting.target.type == 'specific'" class="mt-2 space-y-4">
                        <div class="flex gap-x-4 items-center">
                            <div>The announcement should</div>
                            <div class="w-24">
                                <Select v-model="specificNew.will" :options="['show', 'hide']" placeholder="Select a City" class="w-full" />
                            </div>
                            <div>if URL:</div>
                        </div>

                        <div>
                            <div class="flex gap-x-2">
                                <div class="w-32">
                                    <Select v-model="specificNew.when" :options="['contain', 'matches']" placeholder="When?" class="w-full" />
                                </div>
                                <div class="min-w-80 w-fit max-w-96">
                                    <InputGroup v-show="specificNew.when === 'matches'">
                                        <InputGroupAddon>awa.test/</InputGroupAddon>
                                        <InputText type="text" v-model="specificNew.url" placeholder="blog/subpage" />
                                    </InputGroup>
                                    <InputText v-show="specificNew.when === 'contain'" v-model="specificNew.url" fluid type="text" placeholder="blog/subpage" />
                                </div>
                                <Button @click="() => addSpecificPage()" :style="'secondary'" label="Add" :disabled="!specificNew.url" />
                            </div>

                            <div class="text-xs italic mt-2 text-gray-500">
                                <FontAwesomeIcon icon='fal fa-info-circle' class='text-sm' fixed-width aria-hidden='true' />
                                Put '*' to select the subpages as well (example: blog/subpage/* will affect blog/subpage/aromatheraphy/diffuser/lavender)
                            </div>
                        </div>

                        <!-- Section: Show URL list -->
                        <div v-if="announcementSetting.target.specific.filter(item => item.will === 'show').length">
                            <div>Show:</div>
                            <TransitionGroup name="list" tag="ul" class="bg-slate-200 px-2 py-2 rounded">
                                <template v-for="(spec, specIndex) in announcementSetting.target.specific" :key="`specshow${specIndex}`">
                                    <li v-if="spec.will === 'show'">
                                        <template v-if="spec.when === 'contain'">If <span class="italic">contain</span> <span class="font-bold">{{ spec.url }}</span> in the <Tag label="URL" /></template>
                                        <template v-if="spec.when === 'matches'">If <Tag label="URL" /><span class="italic">matches</span> in <span class="font-bold">{{ spec.url }}</span> </template>
                                        <div @click="() => onDeleteSpecific(specIndex)" class="px-1 py-px inline cursor-pointer text-red-300 hover:text-red-500">
                                            <FontAwesomeIcon icon='fal fa-times' class='' fixed-width aria-hidden='true' />
                                        </div>
                                    </li>
                                </template>
                            </TransitionGroup>
                        </div>

                        <!-- Section: Hide URL list -->
                        <div v-if="announcementSetting.target.specific.filter(item => item.will === 'hide').length">
                            <div>Hide:</div>
                            <TransitionGroup name="list" tag="ul" class="bg-slate-200 px-2 py-2 rounded">
                                <template v-for="(spec, specIndex) in announcementSetting.target.specific" :key="`spechide${specIndex}`">
                                    <li v-if="spec.will === 'hide'">
                                        <template v-if="spec.when === 'contain'">If <span class="italic">contain</span> <span class="font-bold">{{ spec.url }}</span> in the <Tag label="URL" /></template>
                                        <template v-if="spec.when === 'matches'">If <Tag label="URL" /><span class="italic">matches</span> in <span class="font-bold">{{ spec.url }}</span> </template>
                                        <div @click="() => onDeleteSpecific(specIndex)" class="px-1 py-px inline cursor-pointer text-red-300 hover:text-red-500">
                                            <FontAwesomeIcon icon='fal fa-times' class='' fixed-width aria-hidden='true' />
                                        </div>
                                    </li>
                                </template>
                            </TransitionGroup>
                        </div>

                    </div>
                </div>
            </fieldset>

            <div v-if="false" class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Profile</h2>
                    <p class="mt-1 text-sm/6 text-gray-600">
                        This information will be displayed publicly so be careful what you share.
                    </p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-4">
                            <label for="username" class="block text-sm/6 font-medium text-gray-900">Username</label>
                            <div class="mt-2">
                                <div
                                    class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                    <span
                                        class="flex select-none items-center pl-3 text-gray-500 sm:text-sm">workcation.com/</span>
                                    <input type="text" name="username" id="username" autocomplete="username"
                                        class="block flex-1 border-0 bg-transparent py-1.5 pl-1 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm/6"
                                        placeholder="janesmith" />
                                </div>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="about" class="block text-sm/6 font-medium text-gray-900">About</label>
                            <div class="mt-2">
                                <textarea id="about" name="about" rows="3"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                            </div>
                            <p class="mt-3 text-sm/6 text-gray-600">Write a few sentences about yourself.</p>
                        </div>

                        <div class="col-span-full">
                            <label for="photo" class="block text-sm/6 font-medium text-gray-900">Photo</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <UserCircleIcon class="h-12 w-12 text-gray-300" aria-hidden="true" />
                                <button type="button"
                                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">Change</button>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="cover-photo" class="block text-sm/6 font-medium text-gray-900">Cover
                                photo</label>
                            <div
                                class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10">
                                <div class="text-center">
                                    <PhotoIcon class="mx-auto h-12 w-12 text-gray-300" aria-hidden="true" />
                                    <div class="mt-4 flex text-sm/6 text-gray-600">
                                        <label for="file-upload"
                                            class="relative cursor-pointer rounded-md bg-white font-semibold text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-600 focus-within:ring-offset-2 hover:text-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="file-upload" name="file-upload" type="file" class="sr-only" />
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs/5 text-gray-600">PNG, JPG, GIF up to 10MB</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Personal Information</h2>
                    <p class="mt-1 text-sm/6 text-gray-600">Use a permanent address where you can receive mail.</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="first-name" class="block text-sm/6 font-medium text-gray-900">First name</label>
                            <div class="mt-2">
                                <input type="text" name="first-name" id="first-name" autocomplete="given-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="last-name" class="block text-sm/6 font-medium text-gray-900">Last name</label>
                            <div class="mt-2">
                                <input type="text" name="last-name" id="last-name" autocomplete="family-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
                            <div class="mt-2">
                                <input id="email" name="email" type="email" autocomplete="email"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="country" class="block text-sm/6 font-medium text-gray-900">Country</label>
                            <div class="mt-2">
                                <select id="country" name="country" autocomplete="country-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm/6">
                                    <option>United States</option>
                                    <option>Canada</option>
                                    <option>Mexico</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-span-full">
                            <label for="street-address" class="block text-sm/6 font-medium text-gray-900">Street
                                address</label>
                            <div class="mt-2">
                                <input type="text" name="street-address" id="street-address"
                                    autocomplete="street-address"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div class="sm:col-span-2 sm:col-start-1">
                            <label for="city" class="block text-sm/6 font-medium text-gray-900">City</label>
                            <div class="mt-2">
                                <input type="text" name="city" id="city" autocomplete="address-level2"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="region" class="block text-sm/6 font-medium text-gray-900">State /
                                Province</label>
                            <div class="mt-2">
                                <input type="text" name="region" id="region" autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="postal-code" class="block text-sm/6 font-medium text-gray-900">ZIP / Postal
                                code</label>
                            <div class="mt-2">
                                <input type="text" name="postal-code" id="postal-code" autocomplete="postal-code"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm/6" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Notifications</h2>
                    <p class="mt-1 text-sm/6 text-gray-600">We'll always let you know about important changes, but you
                        pick what else you want to hear about.</p>

                    <div class="mt-10 space-y-10">
                        <fieldset>
                            <legend class="text-sm/6 font-semibold text-gray-900">By Email</legend>
                            <div class="mt-6 space-y-6">
                                <div class="relative flex gap-x-3">
                                    <div class="flex h-6 items-center">
                                        <input id="comments" name="comments" type="checkbox"
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                                    </div>
                                    <div class="text-sm/6">
                                        <label for="comments" class="font-medium text-gray-900">Comments</label>
                                        <p class="text-gray-500">Get notified when someones posts a comment on a
                                            posting.</p>
                                    </div>
                                </div>
                                <div class="relative flex gap-x-3">
                                    <div class="flex h-6 items-center">
                                        <input id="candidates" name="candidates" type="checkbox"
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                                    </div>
                                    <div class="text-sm/6">
                                        <label for="candidates" class="font-medium text-gray-900">Candidates</label>
                                        <p class="text-gray-500">Get notified when a candidate applies for a job.</p>
                                    </div>
                                </div>
                                <div class="relative flex gap-x-3">
                                    <div class="flex h-6 items-center">
                                        <input id="offers" name="offers" type="checkbox"
                                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" />
                                    </div>
                                    <div class="text-sm/6">
                                        <label for="offers" class="font-medium text-gray-900">Offers</label>
                                        <p class="text-gray-500">Get notified when a candidate accepts or rejects an
                                            offer.</p>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>


    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <!-- <HeaderListModal 
            :onSelectBlock
            :webBlockTypes="selectedWebBlock"
            :currentTopbar="usedTemplates.topBar"
        /> -->

        <div class="h-[500px]">
            <AnnouncementTemplateList @afterSubmit="() => isModalOpen = false" />
        </div>
    </Modal>
</template>

<style lang="scss" scoped>

.moveable-control-box {

}

.moveable-control.moveable-origin {
    visibility: hidden !important;
}

:deep(.p-select-label) {
    padding-right: 0px !important;
}


.list-move, /* apply transition to moving elements */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(30px);
}

/* ensure leaving items are taken out of layout flow so that moving
   animations can be calculated correctly. */
.list-leave-active {
  position: absolute;
}

</style>