<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sun, 23 Jul 2023 16:36:45 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref } from 'vue'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faImage, faExpandArrows, faAlignCenter, faTrash, faStopwatch } from "../../../private/pro-light-svg-icons"
import PrimitiveInput from '@/Components/Forms/Fields/Primitive/PrimitiveInput.vue'
import Select from '@/Components/Forms/Fields/Select.vue'
import Radio from '@/Components/Forms/Fields/Radio.vue'
import InputWithAddOn from '@/Components/Forms/Fields/InputWithAddOn.vue'
import SlideBackground from "@/Components/Workshop/Fields/SlideBackground.vue"
import Corners from "@/Components/Workshop/Fields/Corners.vue"
import Delete from "@/Components/Workshop/Fields/Delete.vue"
import Colorpicker from '@/Components/Workshop/Fields/ColorPicker.vue'

import { library } from "@fortawesome/fontawesome-svg-core"
import { trans } from "laravel-vue-i18n"


library.add(faImage, faExpandArrows, faAlignCenter, faTrash, faStopwatch)
const props = defineProps<{
    currentComponentBeenEdited: Object
    blueprint: Array
    remove : Function
    common: any
}>()

// console.log(props.common)


const getComponent = (componentName: string) => {
    const components = {
        'input': PrimitiveInput,
        'radio': Radio,
        'slideBackground': SlideBackground,
        'corners': Corners,
        'colorpicker' : Colorpicker,
    };
    return components[componentName]
};


const current = ref(0);

defineExpose({
    current,
});


const setCurrent=(key)=>{
    if(props.blueprint[key].title == 'delete') props.remove(props.currentComponentBeenEdited)
    else current.value = key
}

</script>

<template>
    <div class="divide-y divide-gray-200 lg:grid grid-flow-col lg:grid-cols-12 lg:divide-y-0 lg:divide-x overflow-auto h-full">

        <!-- Left Tab: Navigation -->
        <aside class="py-0 lg:col-span-3 lg:h-full">
            <nav role="navigation" class="space-y-1">
                <ul>
                    <li v-for="(item, key) in blueprint" @click="setCurrent(key)" :class="[
                        'group cursor-pointer border-l-4 px-3 py-2 flex items-center text-sm font-medium',
                        key == current
                            ? 'bg-gray-100 border-orange-500 hover:bg-gray-100 text-gray-600'
                            : 'border-transparent hover:bg-gray-50 text-gray-500 hover:text-gray-700',
                    ]" :aria-current="key === current ? 'page' : undefined">
                        <FontAwesomeIcon v-if="item.icon" aria-hidden="true" class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500" :icon="item.icon" />
                        <span class="capitalize truncate">{{trans(item.title)}}</span>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Content of forms -->
        <div class="px-4 sm:px-6 md:px-4 pt-6 xl:pt-0 col-span-9 flex flex-grow justify-center">
            <div class="flex flex-col w-full">
                <dl v-for="(fieldData, index ) in blueprint[current].fields" :key="index" class="pb-4 sm:pb-5 sm:gap-4 w-full">
                    <!-- Title -->
                    <dt v-if="fieldData.name != 'image_source' && fieldData.label" class="text-sm font-medium text-gray-500 capitalize">
                        <div class="inline-flex items-start leading-none">
                            <span>{{ fieldData.label }}</span>
                        </div>
                    </dt>

                    <!-- Fields -->
                    <dd class="flex text-sm text-gray-700 sm:mt-0 w-full">
                        <div class="relative flex-grow">
                            <component :is="getComponent(fieldData['type'])" :data="currentComponentBeenEdited"
                                :fieldName="fieldData.name" :fieldData="fieldData" :key="index" :counter="false" :common="common">
                            </component>
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
</template>

