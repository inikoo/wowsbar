<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 14 Aug 2023 10:38:30 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import { ref, reactive, onBeforeMount, watch, onBeforeUnmount } from "vue"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import { capitalize } from "@/Composables/capitalize"
import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue"
import Slider from "@/Components/Slider/Slider.vue"
import SlidesWorkshopAddMode from "@/Components/Workshop/SlidesWorkshopAddMode.vue"
import { cloneDeep} from "lodash"
import { set, onValue, get } from "firebase/database"
import { useLayoutStore } from "@/Stores/layout"
import { usePage } from "@inertiajs/vue3"
import { faUser, faUserFriends } from "@/../private/pro-light-svg-icons"
import { library } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { getDbRef } from '@/Composables/firebase'
import { trans } from "laravel-vue-i18n"
import { router } from '@inertiajs/vue3'
import { faAsterisk } from "@/../private/pro-solid-svg-icons"
import Button from "@/Components/Elements/Buttons/Button.vue"
import Modal from '@/Components/Utils/Modal.vue'
import { faRocketLaunch } from "@/../private/pro-regular-svg-icons"
import {  useForm } from '@inertiajs/vue3'
import ScreenView from "@/Components/ScreenView.vue"
import { notify } from "@kyvg/vue3-notification";

library.add( faAsterisk, faRocketLaunch, faUser, faUserFriends );



const props = defineProps<{
    title: string
    pageHead: object
    firebase: Boolean,
    banner: {
        'slug': string,
        'ulid': string,
        'id': number,
        'code': string,
        'name': string
    }
    bannerLayout: {
        delay: number
        common: {
            centralStage: string
            corners: object
        }
        components: Array<{
            id: number
            ulid: string
            layout: {
                link: null | string
                corners: {
                    bottomRight: {
                        data: {
                            text: string
                            target: string
                        }
                        type: string
                    }
                }
                imageAlt: string
                centralStage: {
                    title: string
                    subtitle: string
                }
            }
            visibility: boolean
            image_id: number
            image_source: string
        }>
    }
    imagesUploadRoute: {
        name: string
        parameters?: Array<string>
    }
}>()




const user = ref(usePage().props.auth.user)
const jumpToIndex = ref(0)
const screenView = ref("")
const data = reactive(cloneDeep(props.bannerLayout))
const setData = ref(false)
const firebase = ref(cloneDeep(props.firebase))
const tenant = useLayoutStore().tenant
const dbPath = 'tenants' + '/' + tenant.slug + '/banner_workshop/' + props.banner.slug
const comment = ref('')

const fetchInitialData = async () => {
    try {
        setData.value = true
        const snapshot = await get(getDbRef(dbPath))//<--dbPath
        if (snapshot.exists()) {
            const firebaseData = snapshot.val()
            if (firebaseData) {
                Object.assign(data, { ...firebaseData })
            } else {
                Object.assign(data, { ...data, ...cloneDeep(props.bannerLayout) })
                await set(getDbRef(dbPath), { ...firebaseData, data })
                return
            }
        } else {
            Object.assign(data, { ...data, ...cloneDeep(props.bannerLayout) })
            await set(getDbRef(dbPath), data)
        }
        setData.value = false
    } catch (error) {
        setData.value = false
        Object.assign(data, { ...data, ...cloneDeep(props.bannerLayout) })
    }
}

onValue(getDbRef(dbPath), (snapshot) => {
    if (snapshot.exists()) {
        const firebaseData = snapshot.val()
        if (firebaseData) {
            Object.assign(data, { ...data, ...firebaseData })
        }
    }
})

const updateData = async () => {
    if (firebase.value) {
        try {
            if (data && setData.value == false) {
                const snapshot = await get(getDbRef(dbPath))
                if (snapshot.exists()) {
                    const firebaseData = snapshot.val()
                    await set(getDbRef(dbPath), { ...firebaseData, ...data })
                }
            }
        } catch (error) {
            console.error('Error updating data:', error)
        }
    }
}

watch(data, updateData, { deep: true })
onBeforeMount(fetchInitialData)

const setDataBeforeLeave = () => {
    const set = { ...data }  // Creating a copy of the data object
    for (const index in set.components) {
        if (set.components[index].user == user.value.username) {
            delete set.components[index].user  // Removing the 'user' property from components
        }
    }
    Object.assign(data, set)  // Assigning the modified 'set' object back to 'data'
    updateData()  // This line should help you see the modified 'data' object
}

onBeforeUnmount(() => {
    setDataBeforeLeave()
})

// window.addEventListener('beforeunload', function (event) {
//     event.returnValue = setDataBeforeLeave() // This message will be shown to the user
// })




const isModalOpen = ref(false)
const routeExit = ref()
const routeSave = ref()

const saveRouteValue=(action)=>{
    if (action.style == "exit") {
        routeExit.value = action
   } if (action.style == "save") {
       routeSave.value = action
   }
}

const routeButton = (action) => {
    if (action.style == "exit") {

        router.visit(route(action['route']['name'], action['route']['parameters']))
    } if (action.style == "save") {
        if(props.banner.state != 'unpublished')isModalOpen.value = true
        else sendDataToServer()
    }
}


const sendDataToServer = async () => {
    const formValues = {
        ...data,
        ...(props.banner.state !== 'unpublished' && { comment: comment.value }),
    };

    const form = useForm(formValues);
    form.patch(
        route(routeSave.value['route']['name'], routeSave.value['route']['parameters'])
        , {
            onSuccess: (res) => {
                isModalOpen.value = false
                router.visit(route(routeExit.value['route']['name'], routeExit.value['route']['parameters']))
                notify({
                    title: "success Update",
                    type: "success",
                    text: "Banner already update and publish",
                });
            },
            onError: errors => {
                notify({
                    title: "Failed to Update Banner",
                    text: errors,
                    type: "error"
                });
            },
        })
};

const ceknotif = () => {
    notify({
        title: "Failed to Update Banner",
        text: 'test',
        type: "error",
    });
}

console.log(props)

</script>


<template layout="TenantApp">
    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
        <div>
            <div class="inline-flex items-start leading-none">
                <FontAwesomeIcon :icon="['fas', 'asterisk']" class="font-light text-[12px] text-red-400 mr-1" />
                <span>{{ trans('Comment') }}</span>
            </div>
            <div class="py-2.5">
                <textarea rows="3" v-model="comment"
                    class="block w-full rounded-md shadow-sm dark:bg-gray-600 dark:text-gray-400 border-gray-300 dark:border-gray-500 focus:border-gray-500 focus:ring-gray-500 sm:text-sm" />
            </div>
            <div class="flex justify-end">
                <Button size="xs" @click="sendDataToServer"
                    class="capitalize inline-flex items-center rounded-md text-sm font-medium shadow-sm gap-x-2">
                    <FontAwesomeIcon :icon="['far', 'fa-rocket-launch']" />
                    {{ trans('Publish') }}
                </Button>
            </div>
        </div>
    </Modal>

    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #button="{ dataPageHead: head }">
            <div class="flex items-center gap-2">

                <span v-for="action in head.data.actions">
                    <Button size="xs" :style="action.style" @click="routeButton(action)"
                        :id="head.getActionLabel(action).replace(' ', '-')"
                        class="capitalize inline-flex items-center rounded-md text-sm font-medium shadow-sm gap-x-2">
                        <FontAwesomeIcon v-if="action.icon && action.icon == 'fad fa-save'" aria-hidden="true"
                            :icon="['fad', 'save']"
                            style="--fa-primary-color: #f3f3f3; --fa-secondary-color: #ff6600; --fa-secondary-opacity: 1;"
                            size="sm" :class="[iconClass]" />
                        <FontAwesomeIcon :icon="head.getActionIcon(action)" aria-hidden="true" />
                        {{ trans(head.getActionLabel(action)) }}
                    </Button>
                        {{ saveRouteValue(action) }}
                </span>
            </div>
        </template>
    </PageHeading>

    <div>
        <!-- Component: The full Slider -->
        <div v-if="data.components.filter((item) => item.ulid != null).length > 0">
            <div class="flex w-full">
                <div class="isolate inline-flex shadow-sm w-3/6">
                    <button type="button" @click="firebase = true" :class="[
                        'relative inline-flex items-center px-3 py-2 text-xs border-r focus:z-10',
                        firebase ? 'bg-gray-200 font-semibold text-gray-700' : 'text-gray-500 hover:bg-gray-100'
                    ]">
                        <FontAwesomeIcon :icon="['fal', 'user-friends']" class="p-1" :class="[firebase ? 'text-orange-500' : 'text-gray-500']"/> Collaborative Work
                    </button>
                    <button type="button" @click="firebase = false" :class="[
                        'relative inline-flex items-center px-3 py-2 text-xs border-r focus:z-10',
                        !firebase ? 'bg-gray-200 font-semibold text-gray-700' : 'text-gray-600 hover:bg-gray-100'
                    ]">
                        <FontAwesomeIcon :icon="['fal', 'user']" class="p-1" :class="[firebase ? 'text-gray-500' : 'text-orange-500']"/> Individual Work
                    </button>
                </div>
                <div class="flex justify-end pr-2 w-3/6">
                    <ScreenView @screenView="(val) => (screenView = val)" />
                </div>
            </div>

            <div class="flex justify-center pr-0.5">
                <Slider :data="data" :jumpToIndex="jumpToIndex" :view="screenView" />
            </div>
            <SlidesWorkshop class="clear-both mt-2 p-2.5" :data="data" @jumpToIndex="(val) => jumpToIndex = val"
                :imagesUploadRoute="imagesUploadRoute" :user="user" />
        </div>

        <!-- Component: Add slide if there is not exist -->
        <div v-if="data.components.filter((item) => item.ulid != null).length == 0">
            <SlidesWorkshopAddMode :data="data" :imagesUploadRoute="imagesUploadRoute" />
        </div>
    </div>
    <div @click="ceknotif">show add</div>
</template>
