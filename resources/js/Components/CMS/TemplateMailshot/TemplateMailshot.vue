<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:48:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import axios from 'axios';
import { notify } from "@kyvg/vue3-notification"
import Button from '@/Components/Elements/Buttons/Button.vue';
import { trans } from 'laravel-vue-i18n'

const props = defineProps<{
    title: string,
    pageHead: object,
    tabs: {
        current: string;
        navigation: object;
    }
    changelog?: object,
    showcase?: object,
    snapshots?: object,
}>()

const emits = defineEmits();
const loadingState = ref(false)
const templates = ref([])

const getTemplates = async () => {
    loadingState.value = true
    try {
        const response = await axios.get(
            route('org.json.email.templates')
        )
        templates.value = response.data
    } catch (error) {
        console.log(error)
        notify({
            title: "Failed to get Templates",
            type: "error"
        });
        loadingState.value = false
    }
}

const selectTemplate=(template)=>{
    emits("changeTemplate", template); 
}

onMounted(() => {
    getTemplates()
})

</script>
  
  
<template layout="OrgApp">
    <div class="grid grid-cols-3 gap-4">
        <div v-for="template in templates" :key="template.slug" class="relative">
            <div class="relative pb-[100%]">
                <img :src="`http://127.0.0.1:5173/resources/art/TemplatesMailshot/Christmas/${template.compiled.image}`" 
                    :alt="template.title" class="absolute inset-0 w-full h-full object-cover rounded-lg" />
            </div>
            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg opacity-0 hover:opacity-100 transition duration-300">
                <!-- <div class="text-white text-center">{{ template.title }}</div> -->
                <div class="text-white text-center">
                <Button :label="trans('Use Template')" @click="selectTemplate(template)"/>
                </div>
            </div>
            <span class="flex justify-center p-2 text-bold">{{ template.compiled.name }}</span>
        </div>
    </div>
</template>



  