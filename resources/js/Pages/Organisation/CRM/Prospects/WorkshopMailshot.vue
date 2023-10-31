<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 13:54:55 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { library } from '@fortawesome/fontawesome-svg-core';
import PageHeading from '@/Components/Headings/PageHeading.vue';
import Publish from '@/Components/Utils/Publish.vue'
import { capitalize } from "@/Composables/capitalize"
import { ref, computed } from "vue"
import { faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup } from '@fal/'
import MailshotWorkshopComponent from "@/Components/Workshop/MailshotWorkshopComponent.vue";
import axios from 'axios'
import { cloneDeep } from 'lodash'

library.add(faSign, faGlobe, faPencil, faSeedling, faPaste, faLayerGroup)

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object,
    workshop?: object,
    imagesUploadRoute: Object,
    setAsReadyRoute: Object,
    updateRoute: Object,
    loadRoute: Object

}>()

const isLoading = ref(false)
const comment = ref('')

const isDataDirty = ref(cloneDeep(props.isDirty))

const sendDataToServer = async () => {
    isLoading.value = true
    try {
        const response = await axios.post(
            route(
                props.setAsReadyRoute.name,
                props.setAsReadyRoute.parameters
            ),
            { comment: comment.value },
        )
        console.log('publish......')
        comment.value = ''
    } catch (error) {
        comment.value = ''
        console.log(error)
    }
    isLoading.value = false
}

const compIsDataFirstTimeCreated = computed(() => {
    return false
})

</script>


<template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <Publish v-model="comment" :isDataFirstTimeCreated="compIsDataFirstTimeCreated" :isHashSame="!isDataDirty"
                :isLoading="isLoading" :saveFunction="sendDataToServer" :firstPublish="websiteState != 'live'" />
        </template>
    </PageHeading>
    <MailshotWorkshopComponent :useBasic="false" :imagesUploadRoute="imagesUploadRoute" :updateRoute="updateRoute"
        :loadRoute="loadRoute" />
</template>

