<script setup lang="ts">
import HyperLink from "../Fields/Hyperlink.vue"
import draggable from "vuedraggable"
import SocialMediaPicker from "@/Components/CMS/Fields/IconPicker/SocialMediaTools.vue"
import { ref, onMounted } from "vue"
import { get } from 'lodash'

const props = defineProps<{
    selectedColums: Function
	tool: Object
	data: Object
}>()

const listData = ref(props.data.column.filter((item) => item.type == 'list')[0])

onMounted(() => {
    const setData = props.data.column.filter((item) => item.type == 'list')
    props.selectedColums(props.data.column.findIndex((item)=>item.id == setData[0].id));
});

</script>

<template>
    <footer class="bg-slate-800">
        <div class="mx-auto max-w-7xl overflow-hidden px-6 py-20 sm:py-24 lg:px-8">
            <!-- Navigations -->
            <nav class="grid grid-cols-2 sm:flex sm:justify-center sm:space-x-12" aria-label="Footer">
                <draggable :list="get(listData, 'data', [])" group="link" itemKey="id" :class="[
                    tool.name === 'grab' ? 'cursor-grab' : 'cursor-pointer',
                    'text-gray-400 hover:text-gray-500 flex space-x-6',
                ]" :disabled="tool.name !== 'grab'">
                    <template #item="{ element: child, index: childIndex }">
                        <div>
                            <div v-if="tool.name !== 'grab'">
                                <HyperLink :formList="{
                                    name: 'name',
                                    link: 'link',
                                }" :useDelete="true" :data="child" label="name"
                                    cssClass="space-y-3 text-sm leading-6 text-gray-600 hover:text-indigo-500"
                                    @onDelete="() => listData.data.splice(childIndex, 1)" />
                            </div>
                            <div v-if="tool.name == 'grab'">
                                <span class="px-4 sm:px-0 text-sm leading-6 text-gray-100 hover:text-gray-400">{{ child.name
                                }}</span>
                            </div>
                        </div>

                    </template>
                </draggable>
            </nav>

            <!-- Social Media -->
            <div class="mt-10 flex justify-center space-x-10">
                <draggable :list="data.social" group="socialMedia" itemKey="id" :class="[
                    tool.name === 'grab' ? 'cursor-grab' : 'cursor-pointer',
                    'text-gray-400  flex space-x-6',
                ]" :disabled="tool.name !== 'grab'">
                    <template #item="{ element: child, index: childIndex }">
                        <div>
                            <span class="sr-only">{{ child.label }}</span>
                            <SocialMediaPicker :data="child"  @OnDelete="data.social.splice(index,1)" />
                        </div>
                    </template>
                </draggable>
            </div>


            <div class="mt-10 flex justify-center text-center text-xs leading-5 text-gray-500">
                &copy; 2023 <span class="font-extrabold mx-1">
                    <HyperLink :useDelete="false" :data="data.copyRight" label="label" :formList="{
                        label: 'label',
                        link: 'link',
                    }" />
                </span>, Inc. All rights reserved.
            </div>
        </div>
    </footer>
</template>
