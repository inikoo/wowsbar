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
    <div class="absolute top-0 right-2 cursor-pointer text-rose-400 hover:text-rose-600" @click="emits('OnClose')">x</div>
    <div class="absolute inset-x-0 min-w-fit top-full text-sm text-gray-300">
        <div class="relative bg-gray-600 border border-gray-500 rounded">
            <div class="mx-auto min-w-full max-w-7xl px-4 sm:px-6">
                <draggable :list="data.featured"  :disabled="tool.name !== 'grab'" :group="`menu${data.name}`" key="id" >
                    <template v-slot:item="{ element: child, index }">
                        <div class="p-2 flex gap-3 items-center" :class="[tool.name !== 'grab' ? 'cursor-pointer' : 'cursor-grab']">
                            <IconPicker :key="child.id" :data="child"/>
                            <HyperLink
                                :formList="{
                                name: 'name',
                                link: 'link'
                                }" :useDelete="true" :data="child" label="name" @OnDelete="()=>{data.featured.splice(index,1)}"
                                cssClass="items-center text-sm font-medium whitespace-nowrap " />
                        </div>
                    </template>
                </draggable>
            </div>
        </div>
    </div>
</template>
