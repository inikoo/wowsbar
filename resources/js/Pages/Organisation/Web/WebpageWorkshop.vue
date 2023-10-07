<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref, watch, reactive } from "vue"
import { capitalize } from "@/Composables/capitalize"
import PageHeading from '@/Components/Headings/PageHeading.vue'
import GrapeEditor from '@/Components/CMS/Workshops/GrapeEditor/GrapeEditor.vue'
import GrapesForm from "grapesjs-plugin-forms";
import TailwindComponents from "grapesjs-tailwind";
import { HeaderPlugins, FooterPlugins } from "@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/CustomBlock";
import Button from '@/Components/Elements/Buttons/Button.vue'
import { trans } from "laravel-vue-i18n"


const props = defineProps<{
    title: string
    pageHead: any
    tabs: {
        current: string
        navigation: object
    }
    updateRoute : object,
    loadRoute : object
    webpageState : String,
    websiteState : String
    publishRoute : Object
    setAsReadyRoute : Object

}>()

const isModalOpen = ref(false)
const comment = ref('')

const sendDataToServer = async () => {
    console.log(RouteActive.value)
    try {
        const response = await axios.post(
            route(
                RouteActive.value.name,
                RouteActive.value.parameters
            ),
            { comment : comment.value },
        );
        if (response) {
            console.log('saving......')
            comment.value = ''
        }
    } catch (error) {
        comment.value = ''
        console.log(error)
    }
}


const chekIsLive  = ()=>{
  if(props.websiteState != 'live') sendDataToServer()
  else isModalOpen.value = true
}

</script>

<template layout="OrgApp">
    <Head :title="capitalize(title)"/>
        <PageHeading :data="pageHead">
        <template #other="{ dataPageHead: head }">
            <div class="flex items-center gap-2">
                <span v-if="websiteState !== 'in-process'">
                    <Button @click="chekIsLive" :label="'Publish'" :style="'save'" icon="far fa-rocket-launch"></Button>
                </span>
                <span v-else>
                    <Button :label="'Set to Ready'"></Button>
                </span>
            </div>
        </template>
    </PageHeading>
    <GrapeEditor
     @changeData="(value)=>data = value"
     :plugins="[HeaderPlugins,FooterPlugins,TailwindComponents]"
     :updateRoute="updateRoute"
     :loadRoute="loadRoute"
     />
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

