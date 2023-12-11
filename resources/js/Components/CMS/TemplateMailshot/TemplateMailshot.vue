<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 30 Oct 2023 12:48:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, onMounted , watch } from 'vue'
import axios from 'axios';
import { notify } from "@kyvg/vue3-notification"
import Button from '@/Components/Elements/Buttons/Button.vue';
import { trans } from 'laravel-vue-i18n'
import Tag from '@/Components/Tag.vue';
import EmptyState from '@/Components/Utils/EmptyState.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faThLarge, faTreeChristmas, faGlassCheers, faBat } from '@fas/'
import { library } from '@fortawesome/fontawesome-svg-core'
import { get } from 'lodash'
import Image from '@/Components/Image.vue'

library.add(faThLarge, faTreeChristmas, faGlassCheers, faBat)

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
const activeCategory = ref(null)

const getTemplates = async () => {
    loadingState.value = true
    try {
        const response = await axios.get(
            route('org.json.email.templates',{category : activeCategory.value}),
        )
        templates.value = Object.values(response.data)
    } catch (error) {
        console.log(error)
        notify({
            title: "Failed to get Templates",
            type: "error"
        });
        loadingState.value = false
    }
}

const selectTemplate = (template) => {
    emits("changeTemplate", template);
}

const categories = [
    { label: trans('All Template'), value: null, icon: 'fas fa-th-large' },
    { label: trans('Christmas'), value: 'Christmas', icon: 'fas fa-tree-christmas' },
    { label: trans('New Year'), value: 'New Year', icon: 'fas fa-glass-cheers' },
    { label: trans('Halloween'), value: 'Halloween', icon: 'fas fa-bat' },
]

watch(activeCategory, () => {
    getTemplates()
})

onMounted(() => {
    getTemplates()
})

</script>

<template layout="OrgApp">
    <div class="text-center text-2xl font-bold mb-4">{{ trans("Available Templates") }}</div>
    <div class="flex flex-wrap justify-center items-center gap-4 m-4">
        <!-- Categories with tags -->
        <div v-for="category in categories" :key="category.value">
            <Tag :label="category.label" :theme="category.value == activeCategory ? 5 : 0" @click="()=> activeCategory = category.value">
                <template #label>
                    <FontAwesomeIcon :icon="category.icon" class='' aria-hidden='true' />
                    {{ category.label }}
                </template>
            </Tag>
        </div>
    </div>

    <!-- Responsive grid layout for templates -->
    <div v-if="templates.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <div v-for="template in templates" :key="template.slug" class="relative w-full">
            <div class="relative pb-[90%] border border-gray-300 rounded-lg overflow-hidden">
                <Image :src="template.image"
                       :alt="template.title" class="absolute inset-0 w-full h-full object-cover rounded-lg" />
            </div>
            <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-lg opacity-0 hover:opacity-100 transition duration-300">
                <div class="text-white text-center">
                    <Button :label="trans('Use Template')" @click="selectTemplate(template)" />
                </div>
            </div>
            <span class="flex justify-center p-2 font-bold">{{ template.compiled.name }}</span>
        </div>
    </div>

    <!-- Handling case when no templates are available -->
   <div v-else class="p-4">
        <EmptyState :data="{
            title: trans('You haven\'t uploaded any templates.'),
            description: trans(''),
        }" />
    </div> 
</template>




