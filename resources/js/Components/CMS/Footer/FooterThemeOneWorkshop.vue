<script setup lang="ts">
import draggable from "vuedraggable";
import Input from "../Fields/Input.vue";
import TextArea from "../Fields/TextArea.vue";
import HyperLink from "../Fields/Hyperlink.vue";
import IconPicker from "../Fields/IconPicker/IconPicker.vue";
import SocialMediaPicker from "@/Components/CMS/Fields/IconPicker/SocialMediaTools.vue";
import { get } from "lodash";
import Image from "@/Components/Image.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { library } from '@fortawesome/fontawesome-svg-core'
import { faEnvelope, faPhone } from '@/../private/pro-solid-svg-icons';
library.add( faEnvelope, faPhone )

const props = defineProps<{
    selectedColums: Function;
    columSelected: {
        type: Object;
        required: false;
    };
    tool: Object;
    data: Object;
    layout : Object
}>();

const emits = defineEmits();

const UploadImage = (file) => {
    const fileData = file.target.files[0];
    emits("uploadImage", fileData);
};

</script>

<template>
    <div class="w-full">
        <footer :style="`background-color: ${layout?.color};`" aria-labelledby="footer-heading">
            <h2 id="footer-heading" class="sr-only">Footer</h2>
            <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
                <div class="xl:grid xl:grid-cols-3 xl:gap-20 items-start">
                    <!-- Box -->
                    <div
                        :class="`grid justify-center space-y-5 rounded-xl bg-${layout.colorScheme}-500 border border-indigo-500 py-4 mb-8 xl:mb-0`">
                        <div class="flex justify-center">
                            <label for="Upload" class="cursor-pointer">
                                <input type="file" id="Upload" accept="image/*" style="display: none"
                                    @change="UploadImage" />
                                <Image :src="data.logoSrc" class="h-21" />
                            </label>
                        </div>
                        <div class="flex justify-center">
                            <div :class="`text-${layout.colorScheme}-100 border-t border-gray-500 w-10/12`" />
                        </div>

                        <div class="flex justify-center space-x-6">
                            <draggable :list="data.social" group="socialMedia" itemKey="id" :class="[
                                tool.name === 'grab'
                                    ? 'cursor-grab'
                                    : 'cursor-pointer',
                              `text-${layout.colorScheme}-400 hover:text-${layout.colorScheme}-500 flex space-x-6`,
                            ]" :disabled="tool.name !== 'grab'">
                                <template #item="{
                                    element: child,
                                    index: childIndex,
                                }">
                                    <div>
                                        <span class="sr-only">{{
                                            child.label
                                        }}</span>
                                        <SocialMediaPicker :data="child"  @OnDelete="data.social.splice(index,1)"  />
                                    </div>
                                </template>
                            </draggable>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <draggable :list="data.columns" group="navigation" itemKey="id" :disabled="tool.name !== 'grab'"
                            :class="[
                                'flex',
                                'gap-8',
                                tool.name !== 'grab'
                                    ? 'cursor-pointer'
                                    : 'cursor-grab',
                            ]">
                            <template #item="{ element, index }">
                                <div :class="[
                                    'space-y-3 w-4/12',
                                    get(columSelected, 'id') !== element.id
                                        ? ''
                                        : 'border',
                                ]" @click="props.selectedColums(index)">
                                    <!-- <h3 class="text-sm font-bold leading-6 text-${layout.colorScheme}-500-700 capitalize">{{ element.title }}</h3> -->
                                    <Input :data="element" keyValue="label" :classCss="`font-bold text-${layout.colorScheme}-500`" />

                                    <!-- If the data type is List -->
                                    <div v-if="element.type == 'list'">
                                        <draggable :list="element.items" group="list" itemKey="name"
                                            :disabled="tool.name !== 'grab'">
                                            <template #item="{
                                                element: child,
                                                index: childIndex,
                                            }">
                                                <ul role="list">
                                                    <li :key="child.name" class="py-1.5">
                                                        <HyperLink :formList="{
                                                            name: 'label',
                                                            link: 'href',
                                                        }" :useDelete="true" :data="child" label="label"
                                                            :cssClass="`text-${layout.colorScheme}-400 pr-2 py-3`" @onDelete="() =>
                                                                    element.data.splice(
                                                                        childIndex,
                                                                        1
                                                                    )
                                                                " />
                                                    </li>
                                                </ul>
                                            </template>
                                        </draggable>
                                    </div>

                                    <!-- If the data type is Description -->
                                    <div v-if="element.type == 'description'">
                                        <TextArea :data="element" dataPath="data" />
                                    </div>

                                    <!-- If the data type is Info -->
                                    <div v-if="element.type == 'info'">
                                        <draggable :list="element.items" group="info" itemKey="name"
                                            :disabled="tool.name !== 'grab'" class="flex flex-col gap-y-2">
                                            <template #item="{
                                                element: child,
                                                index: childIndex,
                                            }">
                                            <div class="grid grid-cols-[auto,1fr] gap-4 items-center justify-start gap-y-3 mb-2.5">
											<div v-if="child.type == 'other'" :class="`w-full flex items-center justify-center text-${layout.colorScheme}-400 gap-3`">
												<div><IconPicker :key="child.title" :data="child.data" /></div>
												<Input :data="child.data" keyValue="label" />
											</div>
											<div v-if="child.type == 'email'" :class="`w-full flex items-center justify-center text-${layout.colorScheme}-400 gap-3`">
												<div><font-awesome-icon :icon="['fas', 'envelope']" /></div>
												<Input :data="child" keyValue="data" />
											</div>
											<div v-if="child.type == 'phone'" :class="`w-full flex items-center justify-center text-${layout.colorScheme}-400 gap-3`">
												<div><font-awesome-icon :icon="['fas', 'phone']" /></div>
												<Input :data="child" keyValue="data" />
											</div>
										</div>
                                            </template>
                                        </draggable>
                                    </div>
                                </div>
                            </template>
                        </draggable>
                    </div>
                </div>
                <div class="mt-16 border-t border-white/10 pt-8 sm:mt-20 lg:mt-24 text-center">
                    <div :class="`text-xs flex justify-center leading-5 text-${layout.colorScheme}-400`">
                        &copy; 2023&nbsp;<span class="font-semibold">
                            <HyperLink :useDelete="false" :data="data.copyright" label="label" :formList="{
                                label: 'label',
                                link: 'link',
                            }" />
                        </span>, Inc. All rights reserved.
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
