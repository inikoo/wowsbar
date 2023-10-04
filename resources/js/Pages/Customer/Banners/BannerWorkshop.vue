<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:25:31 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import { router } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import { notify } from "@kyvg/vue3-notification"
import { ref, reactive, onBeforeMount, watch, onBeforeUnmount } from "vue"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import { capitalize } from "@/Composables/capitalize"
import { faUser, faUserFriends } from "../../../../private/pro-light-svg-icons"
import { library } from "@fortawesome/fontawesome-svg-core"
import { trans } from "laravel-vue-i18n"
import Button from "@/Components/Elements/Buttons/Button.vue"
import BannerWorkshopComponent from '@/Components/Workshop/BannerWorkshopComponent.vue'
import { useLayoutStore } from "@/Stores/layout"
import { cloneDeep, set as setLodash } from "lodash"
import { set, onValue, get } from "firebase/database"
import { getDbRef } from '@/Composables/firebase'
import Modal from '@/Components/Utils/Modal.vue'
// import { useBannerHash } from "@/Composables/useBannerHash.ts"
import { usePage } from "@inertiajs/vue3"

import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faRocketLaunch } from "../../../../private/pro-regular-svg-icons"
import { faAsterisk } from "../../../../private/pro-solid-svg-icons"
import { faSpinnerThird } from '../../../../private/pro-duotone-svg-icons'
library.add( faAsterisk, faRocketLaunch, faUser, faUserFriends, faSpinnerThird );

interface Action {
    type: string,
    style: string
    label: string
    icon: string
    route: {
        name: string
        parameters: {
            banner: string
        }
    }
    method: string
}

const props = defineProps<{
    title: string
    pageHead: any
    banner: {
        slug: string,
        ulid: string,
        id: number,
        code: string,
        name: string
        state: string
    }
    bannerLayout: {
        delay: number
        common: {
            centralStage: string
            corners: object
        }
        components: {
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
        }[]
        hash: number
    }
    imagesUploadRoute: {
        name: string
        parameters?: Array<string>
    },
    autoSaveRoute : {
        name: string
        parameters?: Array<string>
    },
    publishRoute : {
        name: string
        parameters?: Array<string>
    }
}>()


const user = ref(usePage().props.auth.user)
const isModalOpen = ref(false)
const comment = ref('')
const loadingState = ref(false)
const isSetData = ref(false)
const routeExit =  props.pageHead.actions.find((item)=> item.style == "exit")
const dbPath = 'customers' + '/' + useLayoutStore().user.customer.ulid + '/banner_workshop/' + props.banner.slug
const data = reactive(cloneDeep(props.bannerLayout))
let timeoutId: any


const sendDataToServer = async () => {
    // When click 'Publish'
    const formValues = {
        ...deleteUser(),
        ...(props.banner.state !== 'unpublished' && { comment: comment.value }),
    };

    const form = useForm(formValues);
    form.patch(
        route(props.publishRoute['name'], props.publishRoute['parameters']), {
        onSuccess: async (res) => {
            // await set(getDbRef(dbPath), { publishedHash: data.hash })
            isModalOpen.value = false
            router.visit(route(routeExit['route']['name'], routeExit['route']['parameters']))
            notify({
                title: "success Update",
                type: "success",
                text: "Banner already update and publish",
            });
        },
        onError: (errors: any) => {
            console.log(errors)
            notify({
                title: "Failed to update banner",
                text: errors,
                type: "error"
            });
        },
    })
}


const fetchInitialData = async () => {
    try {
        isSetData.value = true
        loadingState.value = true

        const snapshot = await get(getDbRef(dbPath))
        const firebaseData = snapshot.exists() ? snapshot.val() : null

        const newData = { ...(firebaseData || cloneDeep(props.bannerLayout)) }
        Object.assign(data, newData)

        await set(getDbRef(dbPath), { ...firebaseData, ...newData })


    } catch (error) {

        Object.assign(data, cloneDeep(props.bannerLayout))
        notify({
            title: "Failed to get realtime data",
            text: 'please reload and make sure your internet connection is stable',
            type: "error"
        });


    } finally {
        isSetData.value = false
        loadingState.value = false
        if(props.banner.state == 'live'){
            startInterval()
        } 
    }
}


onBeforeMount(fetchInitialData)

const deleteUser = () => {
    const set = { ...data }  // Creating a copy of the data object
    for (const index in set.components) {
        if (set.components[index].user == user.value.username) {
            delete set.components[index].user  // Removing the 'user' property from components
        }
    }
    if (set.common.user) delete set.common.user
    return set
}

const handleKeyDown = () => {
    clearTimeout(timeoutId)
}

const updateData = async () => {
    try {
        handleKeyDown()
        if (data && isSetData.value === false) {
            // setLodash(data,'hash',useBannerHash(data)) // Generate new hash based on data page
            const snapshot = await get(getDbRef(dbPath))
            if (snapshot.exists()) {
                const firebaseData = snapshot.val()
                await set(getDbRef(dbPath), { ...firebaseData, ...data })
                if (props.banner.state == 'unpublished') {
                    clearTimeout(timeoutId)
                    timeoutId = setTimeout(() => {
                        autoSave()
                    }, 5000)
                }
            }
        }
    } catch (error) {
        console.error('Error updating data:', error)
    }
}

onValue(getDbRef(dbPath), (snapshot) => {
    if (snapshot.exists()) {
        const firebaseData = snapshot.val()
        Object.assign(data, { ...data, ...firebaseData })
    }
})

const autoSave = () => {
    const form = useForm(deleteUser());
    form.patch(
        route(props.autoSaveRoute.name, props.autoSaveRoute.parameters), {
        onSuccess: async (res) => {},
        onError: (errors: any) => {
            console.log(errors)
        },
    })
}

watch(data, updateData, { deep: true })


const validationState=()=>{
    if(props.banner.state !== 'live') sendDataToServer()
    else isModalOpen.value = true
}

const intervalAutoSave = ref(null)

onBeforeUnmount(() => {
    stopInterval()
})

const  startInterval=()=>{
    intervalAutoSave.value = setInterval(() => {
        autoSave();
      }, 600000);
    }

const stopInterval=()=>{
      clearInterval(intervalAutoSave.value);
    }

</script>


<template layout="CustomerApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"> 
        <template #other="{ dataPageHead: head }">
            <div class="flex items-center gap-2">
                <Button  label="Publish"  :icon="['far', 'rocket-launch']" @click="validationState()"
                    class="capitalize inline-flex items-center rounded-md text-sm border-none font-medium shadow-sm focus:ring-transparent focus:ring-offset-transparent focus:ring-0">
                </Button>
            </div>
        </template>
    </PageHeading>

    <section>
        <div v-if="loadingState" class="w-full min-h-screen flex justify-center items-center">
            <FontAwesomeIcon icon='fad fa-spinner-third' class='animate-spin h-12 text-gray-600' aria-hidden='true' />
        </div>

        <div v-else>
            <BannerWorkshopComponent
                :data="data"
                :imagesUploadRoute="imagesUploadRoute"
                :user="user.username"
            />
        </div>
    </section>

    <Modal :isOpen="isModalOpen" @onClose="isModalOpen = false">
            <div>
                <div class="inline-flex items-start leading-none">
                    <FontAwesomeIcon :icon="'fas fa-asterisk'" class="font-light text-[12px] text-red-400 mr-1" />
                    <span>{{ trans('Comment') }}</span>
                </div>
                <div class="py-2.5">
                    <textarea rows="3" v-model="comment"
                        class="block w-full rounded-md shadow-sm dark:bg-gray-600 dark:text-gray-400 border-gray-300 dark:border-gray-500 focus:border-gray-500 focus:ring-gray-500 sm:text-sm" />
                </div>
                <div class="flex justify-end">
                    <Button size="xs" @click="sendDataToServer" icon="far fa-rocket-launch" label="Publish" />
                </div>
            </div>
    </Modal>

</template>
