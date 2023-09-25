<script setup lang="ts">
import { faBars, faMagnifyingGlass } from "@fortawesome/free-solid-svg-icons"
import { library } from "@fortawesome/fontawesome-svg-core"
import draggable from "vuedraggable"
import HyperLink from '@/Components/CMS/Fields/Hyperlink.vue'
import IconPicker from '@/Components/CMS/Fields/IconPicker/IconPicker.vue'
library.add(faBars, faMagnifyingGlass)
const emits = defineEmits();
const props = defineProps<{
    data: Object
    tool: Object
}>()

</script>

<template>
    <div class="right-2 cursor-pointer text-rose-400 hover:text-rose-600" @click="(e)=>{e.stopPropagation(),  emits('OnClose')}">x</div>
    <div class="absolute inset-x-0 min-w-fit top-full text-sm text-gray-300">
        <div class="relative bg-gray-100 border border-gray-200 rounded">
            <div class="mx-auto min-w-full max-w-7xl px-4 sm:px-6 lg:px-8">
                <draggable :list="data.items"  :disabled="tool.name !== 'grab'" :group="`menu${data.name}`" key="id" >
                    <template v-slot:item="{ element: child, index }">
                        <div class="p-2 flex gap-3 items-center w-52" :class="[tool.name !== 'grab' ? 'cursor-pointer' : 'cursor-grab']">
                            <IconPicker :key="child.id" :data="child" class="font-medium text-gray-500 p-2 hover:text-gray-700"/>
                            <HyperLink
                                :formList="{
                                label: 'label',
                                link: 'link'
                                }" :useDelete="true" :data="child" label="label" @OnDelete="()=>{data.featured.splice(index,1)}"
                                cssClass="font-medium text-gray-500 p-2 hover:text-gray-700" />
                        </div>
                    </template>
                </draggable>
            </div>
        </div>
    </div>
</template>
