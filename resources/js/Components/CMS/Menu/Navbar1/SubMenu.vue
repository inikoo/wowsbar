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
    <div class="ml-2 cursor-pointer text-rose-500" @click="emits('OnClose')">x</div>
    <div class="absolute inset-x-0 top-full text-sm text-gray-500">
        <div class="absolute inset-0 top-1/2 bg-white shadow" aria-hidden="true"></div>
        <div class="relative bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div>
                    <draggable :list="data.featured"  :disabled="tool.name !== 'grab'" :group="`menu${data.name}`" key="id" class="grid grid-cols-3 gap-x-8 gap-y-4 py-4">
                        <template v-slot:item="{ element: child, index }">
                            <div class="group relative">
                                <div :class="['mt-4', 'block', 'font-medium', 'text-gray-900', 'p-2', tool.name !== 'grab' ? 'cursor-pointer' : 'cursor-grab']">
                                    <span class="absolute inset-0 z-10" aria-hidden="true">
                                        <div class="flex gap-3">
                                            <IconPicker :key="child.id" :data="child"/>
                                            <HyperLink 
                                                :formList="{
                                                name: 'name',
                                                link: 'link'
                                                }" :useDelete="true" :data="child" label="name" @OnDelete="()=>{data.featured.splice(index,1)}"
                                                cssClass="items-center text-sm font-medium text-black" />
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>
    </div>
</template>
