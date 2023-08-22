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
import { cloneDeep, set as setData, isEqual } from "lodash"
import { set, onValue, get } from "firebase/database"
import { useLayoutStore } from "@/Stores/layout"
import { usePage } from "@inertiajs/vue3"
import { faUser, faUserFriends } from "@/../private/pro-light-svg-icons"
import { library } from "@fortawesome/fontawesome-svg-core"
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { getDbRef } from '@/Composables/firebase'
library.add(faUser, faUserFriends)
const props = defineProps<{
    title: string
    pageHead: object
    firebase: Boolean
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
console.log(layout)
const dbPath =  'tenants' +'/'+ tenant.code +'/banner_workshop/'+ props.imagesUploadRoute.arguments.banner

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
            await set(getDbRef(dbPath),  data)
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
            Object.assign(data, { ...data, ...firebaseData})
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
</script>


<template layout="TenantApp">
  <Head :title="capitalize(title)" />
  <PageHeading :data="pageHead" :dataToSubmit="data"></PageHeading>

  <div>
    <!-- First set of components -->
    <div v-if="data.components.filter((item) => item.ulid != null).length > 0">
    <div class="flex w-full">
    <div class="isolate inline-flex shadow-sm w-3/6" >
      <button type="button"  @click="firebase = true" :class="[
    'relative inline-flex items-center bg-white px-3 py-2 text-xs font-semibold border-r hover:bg-gray-50 focus:z-10',
    firebase ? 'text-orange-500' : 'text-gray-900'
  ]"><font-awesome-icon :icon="['fal', 'user']" class="p-1"/> Collaborative Work</button>
      <button type="button" @click="firebase = false" :class="[
    'relative inline-flex items-center bg-white px-3 py-2 text-xs font-semibold border-r hover:bg-gray-50 focus:z-10',
    !firebase ? 'text-orange-500' : 'text-gray-900'
  ]"><font-awesome-icon :icon="['fal', 'user-friends']" class="p-1"/> Individual Work</button>
    </div>
        <div class="flex justify-end pr-2 w-3/6">
          <ScreenView @screenView="(val) => (screenView = val)" />
        </div>
    </div>

      <div class="flex justify-center pr-0.5">
        <Slider :data="data" :jumpToIndex="jumpToIndex" :view="screenView" />
      </div>
      <SlidesWorkshop
        class="clear-both mt-2 p-2.5"
        :data="data"
        @jumpToIndex="(val) => (jumpToIndex = val)"
        :imagesUploadRoute="imagesUploadRoute"
        :user="user"
      />
    </div>

    <!-- Second set of components -->
    <div v-if="data.components.filter((item) => item.ulid != null).length == 0">
      <SlidesWorkshopAddMode :data="data" :imagesUploadRoute="imagesUploadRoute" />
    </div>
  </div>

  <div
    @click="
      () => {
        (jumpToIndex = 3), console.log(data)
      }
    "
  >
    Check data
  </div>
</template>
