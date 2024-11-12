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
import AnnouncementSettings from '@/Components/Workshop/Announcement/AnnouncementSettings.vue'
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'

import { library } from "@fortawesome/fontawesome-svg-core"
import { faGlobe, faImage, faExternalLink, faRocketLaunch, faSave, faUndoAlt, faInfoCircle, faChevronDown, faCircle } from '@fal'
import { faThLarge, faSquare } from '@fas'
import { faCheckCircle } from '@far'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import AnnouncementSideEditor from '@/Components/Workshop/Announcement/AnnouncementSideEditor.vue'
import { notify } from '@kyvg/vue3-notification'
import ScreenView from '@/Components/ScreenView.vue'
import Button from '@/Components/Elements/Buttons/Button.vue'
import LoadingIcon from '@/Components/Utils/LoadingIcon.vue'
import { debounce, remove, set } from 'lodash'
import Modal from '@/Components/Utils/Modal.vue'
import { getAnnouncementComponent } from '@/Composables/useAnnouncement'
import { propertiesToHTMLStyle } from '@/Composables/usePropertyWorkshop'
import { routeType } from '@/types/route'
import EmptyState from '@/Components/Utils/EmptyState.vue'
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import VueDatePicker from '@vuepic/vue-datepicker';
import { useFormatTime } from '@/Composables/useFormatTime'
import PureTextarea from '@/Components/Pure/PureTextarea.vue'

library.add(faGlobe, faImage, faExternalLink, faRocketLaunch, faSave, faUndoAlt, faInfoCircle, faChevronDown, faCircle, faSquare, faThLarge, faCheckCircle)

const props = defineProps<{
    pageHead: {}
    title: string
    // data: {},
    firstBanner?: {
        text: string
        createRoute: {
            name: string
            parameters?: any[]
        }
    }
    announcement_data: {
        code: string
        container_properties: {
            
        }
        created_at: string
        fields: {
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
        id: number
        icon?: string
        name: string
        schedule_at?: string
        schedule_finish_at?: string
        settings: {

        }
        state: string
        status: string
        ulid: string
        updated_at: string
    }
    announcement_list: {}
    routes_list: {
        publish_route: routeType
        update_route: routeType
        reset_route: routeType
    }
    isAnnouncementPublished: boolean
    isAnnouncementActive: boolean
    route_toggle_activated: routeType
}>()

const announcementData = ref(props.announcement_data)
console.log('ann', announcementData.value)
provide('announcementData', announcementData.value)

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
            onError: (error) => {
                console.log('ewew', error)
                notify({
                    title: trans('Something went wrong'),
                    text: trans('Failed to save Announcement data'),
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

const isLoadingComponent = ref<string | null>(null)
const selectedTab = ref('Setting')
const changeTab = async (category: string) => {
    isLoadingComponent.value = category
    selectedTab.value = category
}

// const announcementSetting = ref()

onMounted(() => {
    if (Array.isArray(announcementData.value?.settings) && announcementData.value?.settings?.length === 0) {
        announcementData.value.settings = {
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
        }
    }
})


const newDate = new Date()
const publishStartDate = ref(newDate.setDate(newDate.getDate() + 2))
const publishEndDate = ref(newDate.setDate(newDate.getDate() + 9))

const isModalPublish = ref(false)
const settingPublish = ref({
    start_type: 'instant',  // 'scheduled'
    end_type: newDate.setDate(newDate.getDate() + 2),  // 'unlimited'
})


const isActivated = ref(props.isAnnouncementActive)
const cancelTokenActivate = ref<Function | null>(null)
const onClickToggleActivate = async (newVal: boolean) => {
    if (isActivated.value === newVal) return

    if(cancelTokenActivate.value) {
        cancelTokenActivate.value()
    }
    isActivated.value = newVal
    router[props.route_toggle_activated.method || 'patch'](
        route(props.route_toggle_activated.name, props.route_toggle_activated.parameters),
        { is_published: newVal },
        {
            onCancelToken: (cancelToken) => {
                cancelTokenActivate.value = cancelToken.cancel
            },
            onFinish: () => {
                isActivated.value = props.isAnnouncementPublished
                cancelTokenActivate.value = null
            },
            onSuccess: () => {
                notify({
                    title: trans('Gotcha!'),
                    text: trans('Successfully set the status'),
                    type: 'success',
                })
            },
            onError: () => {
                notify({
                    title: trans('Something went wrong'),
                    text: trans('Failed to update the status'),
                    type: 'error',
                })
            },
        }
    )
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
                <Button @click="onReset" label="Reset" v-tooltip="'Reset data to last publish'" :loading="isLoadingSave" :style="'negative'" icon="fal fa-undo-alt" />
                <Button @click="onSave" label="save" :loading="isLoadingSave" :style="'tertiary'" icon="fal fa-save" />
                <!-- <Button @click="() => false" label="Stop now" :loading="isLoadingSave" :style="'red'" icon="fas fa-square" /> -->

                <div v-if="route_toggle_activated" class="flex items-center">
                    <div class="grid grid-cols-2 cursor-pointer rounded overflow-hidden select-none ring-1 ring-gray-300">
                        <div @click="onClickToggleActivate(false)"
                            class="py-1.5 px-3 flex justify-center items-center gap-x-1 capitalize transition-all"
                            :class="[!isActivated ? 'bg-red-600 text-gray-100' : 'bg-gray-100/70 text-red-400 hover:bg-red-200/70']">
                            {{ trans('Inactive') }}
                            <LoadingIcon v-if="!isActivated && cancelTokenActivate" size="sm" />
                            <FontAwesomeIcon v-else-if="!isActivated" icon='far fa-check-circle' size="sm" class='' fixed-width aria-hidden='true' />
                            <FontAwesomeIcon v-else="!cancelTokenActivate" icon='fal fa-circle' size="sm" class='' fixed-width aria-hidden='true' />
                        </div>
                        <div @click="onClickToggleActivate(true)"
                            class="py-1.5 px-3 flex justify-center items-center gap-x-1 capitalize transition-all"
                            :class="[isActivated ? 'bg-green-600 text-green-100' : 'bg-gray-100/70 text-gray-400 hover:bg-green-200/70']">
                            {{ trans('Active') }}
                            <LoadingIcon v-if="isActivated && cancelTokenActivate" size="sm" />
                            <FontAwesomeIcon v-else-if="isActivated" icon='far fa-check-circle' size="sm" class='' fixed-width aria-hidden='true' />
                            <FontAwesomeIcon v-else="!cancelTokenActivate" icon='fal fa-circle' size="sm" class='' fixed-width aria-hidden='true' />
                        </div>
                    </div>
                </div>

                <div class="flex items-center">
                    <Button @click="onPublish" label="Publish now" :loading="isLoadingSave" iconRight="fal fa-rocket-launch" class="rounded-r-none" />
                    
                    <Button @click="() => isModalPublish = true" :loading="isLoadingSave" class="rounded-l-none pl-2 pr-2">
                        <template #icon>
                            <div>
                                <FontAwesomeIcon icon='fal fa-cog' class='' fixed-width aria-hidden='true' />
                            </div>
                        </template>
                    </Button>
                </div>
            </div>
        </template>
    </PageHeading>

    <!-- Section: Tab selector -->
    <div class="mx-auto max-w-md px-2 sm:px-0 my-4">
        <!-- {{ selectedTab }} -->
        <TabGroup>
            <TabList class="flex space-x-1 rounded-xl bg-slate-600 p-1">
                <Tab
                    v-for="category in ['Workshop', 'Setting']"
                    as="template"
                    :key="category"
                    v-slot="{ selected }"
                    @click="async () => selectedTab === category ? '' : changeTab(category)"
                >
                    <button :class="[
                        'px-8 w-full rounded-lg py-2.5 text-sm font-medium leading-5 ring-white/60 ring-offset-2 ring-offset-blue-400 focus:outline-none focus:ring-2',
                        selected
                            ? 'bg-white text-slate-700 shadow'
                            : 'text-blue-100 hover:bg-white/[0.12] hover:text-white',
                        ]"
                    >
                        {{ category }}
                        <LoadingIcon v-if="isLoadingComponent === category" />
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

            <AnnouncementSideEditor v-if="announcementData.template_code" @onMounted="() => isLoadingComponent = null" />
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
                <div class="h-full w-full bg-white relative">
                    <div v-if="announcementData.template_code" ref="_parentComponent" :style="{
                            ...propertiesToHTMLStyle(announcementData.container_properties, { toRemove: styleToRemove}),
                            position: announcementData.container_properties?.position?.type === 'fixed' ? 'absolute' : announcementData.container_properties?.position?.type
                        }">
                        <component :is="getAnnouncementComponent(announcementData.template_code)"
                            :announcementData="announcementData" :key="announcementData.template_code" isEditable
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
            <AnnouncementSettings
                @onMounted="() => isLoadingComponent = null"
            />

        </div>
    </div>


    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <div class="h-[500px]">
            <AnnouncementTemplateList @afterSubmit="() => isModalOpen = false" />
        </div>
    </Modal>

    <Modal :isOpen="isModalPublish" @onClose="isModalPublish = false" width="w-[500px]">
        <div class="grid grid-cols-1 h-fit gap-y-4 ">
            <fieldset class="">
                <div class="text-sm/6 font-semibold ">Start date</div>
                <div class="bg-gray-50 rounded p-4 border border-gray-200 space-y-6">
                    <div class="flex items-center gap-x-3">
                        <input
                            value="instant"
                            @input="(val: string) => set(settingPublish, 'start_type', val.target.value)"
                            :checked="settingPublish.start_type ==  'instant'"
                            id="inp-publish-now"
                            name="inp-publish-now"
                            type="radio"
                            class="cursor-pointer h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                        />
                        <label for="inp-publish-now" class="block text-sm/6 cursor-pointer ">Publish now</label>
                    </div>
                    
                    <div class="flex items-center gap-x-3">
                        <input
                            value="scheduled"
                            @input="(val: string) => set(settingPublish, 'start_type', val.target.value)"
                            :checked="settingPublish.start_type ==  'scheduled'"
                            id="inp-publish-schedule"
                            name="inp-publish-schedule"
                            type="radio"
                            class="cursor-pointer h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                        />
                        <!-- <label for="inp-publish-schedule" class="block text-sm/6 font-medium cursor-pointer ">Scheduled</label> -->
                        <VueDatePicker
                            v-model="publishStartDate"
                            time-picker-inline
                            auto-apply
                            :min-date="new Date()"
                            :clearable="false"
                            class="w-fit"
                        >
                            <template #trigger>
                                <Button :style="'tertiary'" size="xs" :disabled="settingPublish.start_type !==  'scheduled'">
                                    {{ useFormatTime(publishStartDate, {formatTime: 'hm'}) }}
                                </Button>
                            </template>
                        </VueDatePicker>

                    </div>

                </div>
            </fieldset>

            <fieldset class="">
                <div class="text-sm/6 font-semibold ">Finish date</div>
                <div class="bg-gray-50 rounded p-4 border border-gray-200 space-y-6">
                    <div class="flex items-center gap-x-3">
                        <input
                            value="instant"
                            @input="(val: string) => set(settingPublish, 'end_type', val.target.value)"
                            :checked="settingPublish.end_type ==  'instant'"
                            id="inp-finish-unlimited"
                            name="inp-finish-unlimited"
                            type="radio"
                            class="cursor-pointer h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                        />
                        <label for="inp-finish-unlimited" class="block text-sm/6 font-medium cursor-pointer ">Until I deactivated</label>
                    </div>
                    
                    <div class="flex items-center gap-x-3">
                        <input
                            value="scheduled"
                            @input="(val: string) => set(settingPublish, 'end_type', val.target.value)"
                            :checked="settingPublish.end_type ==  'scheduled'"
                            id="inp-finish-scheduled"
                            name="inp-finish-scheduled"
                            type="radio"
                            class="cursor-pointer h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-600"
                        />
                        <!-- <label for="inp-finish-scheduled" class="block text-sm/6 font-medium cursor-pointer ">Scheduled</label> -->
                        <VueDatePicker
                            v-model="publishEndDate"
                            time-picker-inline
                            auto-apply
                            :min-date="new Date()"
                            :clearable="false"
                            class="w-fit"
                        >
                            <template #trigger>
                                <Button :style="'tertiary'" size="xs" :disabled="settingPublish.end_type !==  'scheduled'">
                                    {{ useFormatTime(publishEndDate, {formatTime: 'hm'}) }}
                                </Button>
                            </template>
                        </VueDatePicker>
                    </div>
                </div>
            </fieldset>

            
            <fieldset class="">
                <div class="text-sm/6 font-semibold ">Description</div>
                <PureTextarea
                    :placeholder="trans('My first publish')"
                    inputClass="bg-gray-50"
                />
            </fieldset>

            <Button
                label="Publish"
                full
            />
        </div>

        <div class="mt-4">
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

</style>