<script setup lang="ts">
import { ref, reactive, onBeforeMount, watch, onBeforeUnmount, computed } from "vue"
// import { usePage } from "@inertiajs/vue3"
// import { useForm } from '@inertiajs/vue3'
// import { router } from '@inertiajs/vue3'
// import { notify } from "@kyvg/vue3-notification"

import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue"
import SliderLandscape from "@/Components/Slider/SliderLandscape.vue"
import SliderSquare from "@/Components/Slider/SliderSquare.vue"
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

const jumpToIndex = ref('')
const screenView = ref("")

</script>

<template>
    <!-- <pre>{{ data.components }}</pre> -->
    <div v-if="data.components.filter((item: any) => item.ulid != null).length > 0" class="w-full">
        <!-- Button: Screen -->
        <div class="flex justify-end pr-2">
            <ScreenView @screenView="(val) => (screenView = val)" />
        </div>

        <!-- Banner: Square or Landscape -->
        <div class="flex pr-0.5"
            :class="[data.type === 'square' ? 'justify-start 2xl:justify-center' : 'justify-center']"
        >
            <div v-if="data.type === 'square'"
                class="w-full min-h-[250px] max-h-[400px]"
            >
                <SliderSquare :data="data" :jumpToIndex="jumpToIndex" :view="screenView" />
            </div>
            <SliderLandscape v-else :data="data" :jumpToIndex="jumpToIndex" :view="screenView" />
        </div>
        
        <!-- Editor -->
        <SlidesWorkshop :bannerType="banner.type" class="clear-both mt-2 p-2.5" :data="data" @jumpToIndex="(UlidOfSlide) => jumpToIndex = UlidOfSlide"
            :imagesUploadRoute="imagesUploadRoute" :user="user" :screenView="screenView" />
    </div>

    <!-- Section: Add slide if there is not exist -->
    <div v-else>
        <SlidesWorkshopAddMode :data="data" :imagesUploadRoute="imagesUploadRoute" />
    </div>
</template>