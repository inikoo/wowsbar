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
import Range from "@/Components/Workshop/Fields/Range.vue"
import { library } from "@fortawesome/fontawesome-svg-core"
import { trans } from "laravel-vue-i18n"


library.add(faImage, faExpandArrows, faAlignCenter, faTrash, faStopwatch)
const props = defineProps<{
    currentComponentBeenEdited: Object,
    blueprint: Array,
    remove : Function
}>()



const getComponent = (componentName: string) => {
    const components = {
        'input': PrimitiveInput,
        'inputWithAddOn': InputWithAddOn,
        'select': Select,
        'radio': Radio,
        'slideBackground': SlideBackground,
        'corners': Corners,
        'delete': Delete,
        'range': Range,
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
                        key == current
                            ? 'bg-gray-100 border-orange-500 text-orange-700 hover:bg-gray-100 hover:text-orange-700'
                            : 'border-transparent text-gray-900 hover:bg-gray-50 hover:text-gray-900',
                        'cursor-pointer group border-l-4 px-3 py-2 flex items-center text-sm font-medium',
                    ]" :aria-current="key === current ? 'page' : undefined">
                        <FontAwesomeIcon v-if="item.icon" aria-hidden="true" :class="[
                            key === current
                                ? 'text-orange-500 group-hover:text-orange-500'
                                : 'text-gray-400 group-hover:text-gray-500',
                            'flex-shrink-0 -ml-1 mr-3 h-6 w-6',
                        ]" :icon="item.icon" />

                        <span class="capitalize truncate">{{trans(item.title)}}</span>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Content of forms -->
        <div class="px-4 sm:px-6 md:px-4 col-span-9">
            <div class="divide-y divide-grey-200 flex flex-col">
                <div class="mt-2 pt-3">
                    <div v-for="(fieldData, index ) in blueprint[current].fields" :key="index" class="">
                        <dl class="divide-y divide-green-200  ">
                            <div class="pb-4 sm:pb-5 sm:gap-4 max-w-2xl ">

                                <!-- Title -->
                                <dt class="text-sm font-medium text-gray-500 capitalize">
                                    <div class="inline-flex items-start leading-none">
                                        <span>{{ fieldData.label }}</span>
                                    </div>
                                </dt>

                                <!-- Fields -->
                                <dd class="">
                                    <div class="mt-1 flex text-sm text-gray-700 sm:mt-0">
                                        <div class="relative flex-grow">
                                            <component :is="getComponent(fieldData['type'])" :data="currentComponentBeenEdited"
                                                :fieldName="fieldData.name" :fieldData="fieldData" :key="index" :counter="false">
                                            </component>
                                        </div>
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

