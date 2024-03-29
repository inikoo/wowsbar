<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 02 Oct 2023 03:25:31 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import {Head, router, useForm, usePage} from "@inertiajs/vue3"
import {notify} from "@kyvg/vue3-notification"
import {ref, reactive, onBeforeMount, watch, onBeforeUnmount, computed} from "vue"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import {capitalize} from "@/Composables/capitalize"
import {library} from "@fortawesome/fontawesome-svg-core"
import BannerWorkshopComponent from '@/Components/Workshop/BannerWorkshopComponent.vue'
import {useLayoutStore} from "@/Stores/layout"
import {cloneDeep} from "lodash"
import {set, onValue, get} from "firebase/database"
import {getDbRef} from '@/Composables/firebase'

import {useBannerHash} from "@/Composables/useBannerHash"
import Publish from "@/Components/Utils/Publish.vue"
import { BannerWorkshop, CornersData, SlideWorkshopData } from '@/types/BannerWorkshop'
import { Banner } from '@/types/banner'
import { routeType } from '@/types/route'

import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome"
import {faUser, faUserFriends} from '@fal'
import {faRocketLaunch} from '@far'
import {faAsterisk} from '@fas'
import {faSpinnerThird} from '@fad'

library.add(faAsterisk, faRocketLaunch, faUser, faUserFriends, faSpinnerThird);

const props = defineProps<{
    title: string
    pageHead: any
    banner: Banner
    bannerLayout: BannerWorkshop
    imagesUploadRoute: routeType
    autoSaveRoute: routeType
    publishRoute: routeType
    currentView?: Object
}>()


const user = ref(usePage().props.auth.user)
const isLoading = ref(false)
const comment = ref('')
const loadingState = ref(false)
const isSetData = ref(false)

const routeExit = props.pageHead.actions.find((item) => item.style == "exit")
const dbPath = 'customers' + '/' + useLayoutStore().user.customer.ulid + '/banner_workshop/' + props.banner.slug
const data = reactive(cloneDeep(props.bannerLayout))
let timeoutId: any

const compCurrentHash = computed(() => {
    return useBannerHash(data)
})

// When click 'Publish'
const sendDataToServer = async () => {
    isLoading.value = true
    const formValues = {
        ...deleteUser(),
        ...(props.banner.state !== 'unpublished' && {comment: comment.value}),
    }
    const form = useForm(formValues)

    form.patch(
        route(props.publishRoute['name'], props.publishRoute['parameters']), {
            onSuccess: async () => {
                try {
                    await set(getDbRef(dbPath), {published_hash: compCurrentHash.value})
                } catch (error) {
                    console.log("============")
                    console.log(error)
                }
                isLoading.value = false
                router.visit(route(routeExit['route']['name'], routeExit['route']['parameters']))
                notify({
                    title: "Success!",
                    type: "success",
                    text: "Banner been updated and published.",
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

        const newData = {...(firebaseData || cloneDeep(props.bannerLayout))}
        Object.assign(data, newData)

        await set(getDbRef(dbPath), {...firebaseData, ...newData})


    } catch (error) {

        Object.assign(data, cloneDeep(props.bannerLayout))


    } finally {
        isSetData.value = false
        loadingState.value = false
        if (props.banner.state == 'live') {
            startInterval()
        }
    }
}


onBeforeMount(fetchInitialData)

const deleteUser = () => {
    const set = cloneDeep(data) // Creating a copy of the data object
    for (const index in set.components) {
        if (set.components[index].user == user.value.username) {
            delete set.components[index].user  // Removing the 'user' property from components
        }
    }
    if (set.common?.user) delete set.common.user
    return set
}

const handleKeyDown = () => {
    clearTimeout(timeoutId)
}

const updateData = async () => {
    try {
        handleKeyDown()
        if (data && isSetData.value === false) {
            const snapshot = await get(getDbRef(dbPath))
            if (snapshot.exists()) {
                const firebaseData = snapshot.val()
                await set(getDbRef(dbPath), {...firebaseData, ...data})
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
        Object.assign(data, {...data, ...firebaseData})
    }
})

const autoSave = () => {
    const form = useForm(deleteUser());
    form.patch(
        route(props.autoSaveRoute.name, props.autoSaveRoute.parameters), {
            preserveScroll: true,
            onError: (errors: any) => {
                console.log(errors)
            },
        })
}

watch(data, updateData, {deep: true})


// const validationState=()=>{
//     if(props.banner.state !== 'live') sendDataToServer()
//     else isPopoverOpen.value = true
// }

const intervalAutoSave = ref(null)


const startInterval = () => {
    intervalAutoSave.value = setInterval(() => {
        autoSave();
    }, 600000);
}

const stopInterval = () => {
    clearInterval(intervalAutoSave.value);
}


const compIsHashSameWithPrevious = computed(() => {
    // Check is current Hash is same with previous Hash (that have been published)
    return compCurrentHash.value == data.published_hash
})

const compIsDataFirstTimeCreated = computed(() => {
    // Check is current Hash is same as initial Hash
    return compCurrentHash.value == "fd186208ae9dab06d40e49141f34bef9"
})

const setDataBeforeleaves=()=>{
    const newData = deleteUser()
    Object.assign(data,newData)
    updateData()

}

window.addEventListener('beforeunload', setDataBeforeleaves())

onBeforeUnmount(() => {
    setDataBeforeleaves()
    stopInterval()
})

</script>


<template layout="CustomerApp">
    <Head :title="capitalize(title)"/>
    <!-- <pre>{{ props.banner.type }}</pre> -->
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <Publish
                v-if="data.components.length > 0"
                v-model="comment"
                :isDataFirstTimeCreated="compIsDataFirstTimeCreated"
                :isHashSame="compIsHashSameWithPrevious"
                :isLoading="isLoading"
                :saveFunction="sendDataToServer"
                :firstPublish="banner.state == 'unpublished'"
            />
        </template>
    </PageHeading>

    <section>
        <div v-if="loadingState" class="w-full min-h-screen flex justify-center items-center">
            <FontAwesomeIcon icon='fad fa-spinner-third' class='animate-spin h-12 text-gray-600' aria-hidden='true'/>
        </div>

        <div v-else>
            <BannerWorkshopComponent
                :data="data"
                :imagesUploadRoute="imagesUploadRoute"
                :user="user.username"
                :banner="banner"
            />
        </div>
    </section>
</template>
