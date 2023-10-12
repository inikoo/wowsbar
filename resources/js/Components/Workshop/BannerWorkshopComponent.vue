<script setup lang="ts">
import { ref, reactive, onBeforeMount, watch, onBeforeUnmount, computed } from "vue"
// import { usePage } from "@inertiajs/vue3"
// import { useForm } from '@inertiajs/vue3'
// import { router } from '@inertiajs/vue3'
// import { notify } from "@kyvg/vue3-notification"

import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue"
import Slider from "@/Components/Slider/Slider.vue"
import SlidesWorkshopAddMode from "@/Components/Workshop/SlidesWorkshopAddMode.vue"
import ScreenView from "@/Components/ScreenView.vue"
// import Button from "@/Components/Elements/Buttons/Button.vue"

// import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
// import { trans } from "laravel-vue-i18n"

const props = defineProps<{
    data: any
    imagesUploadRoute: any
    user: any
    banner: any
}>()

const jumpToIndex = ref(0)
const screenView = ref("")

</script>

<template>
    <div v-if="data.components.filter((item: any) => item.ulid != null).length > 0" class="w-full">
        <div class="flex justify-end pr-2">
            <ScreenView @screenView="(val) => (screenView = val)" />
        </div>

        <div class="flex justify-center pr-0.5">
            <Slider :bannerType="banner.type" :data="data" :jumpToIndex="jumpToIndex" :view="screenView" />
        </div>
        
        <SlidesWorkshop :bannerType="banner.type" class="clear-both mt-2 p-2.5" :data="data" @jumpToIndex="(val) => jumpToIndex = val"
            :imagesUploadRoute="imagesUploadRoute" :user="user" :screenView="screenView" />
    </div>

    <!-- Component: Add slide if there is not exist -->
    <div v-if="data.components.filter((item: any) => item.ulid != null).length == 0">
        <SlidesWorkshopAddMode :bannerType="banner.type" :data="data" :imagesUploadRoute="imagesUploadRoute" />
    </div>

</template>