<script setup lang="ts">
import Image from '@/Components/Image.vue'

const props = defineProps<{
    dataFooter: any
    dataLayout: any
}>()
</script>

<template>
        <div class="w-full">
            <footer :style="`background-color: ${dataLayout.footer.color};`" aria-labelledby="footer-heading">
                <h2 id="footer-heading" class="sr-only">Footer</h2>
                <div class="mx-auto max-w-7xl px-6 pb-8 pt-16 sm:pt-24 lg:px-8 lg:pt-32">
                    <div class="xl:grid xl:grid-cols-3 xl:gap-20 items-start">
                        <!-- Box -->
                        <div
                            :class="`grid justify-center space-y-5 rounded-xl bg-${dataLayout.footer.colorScheme}-500 border border-indigo-500 py-4 mb-8 xl:mb-0`">
                            <div class="flex justify-center">
                                <Image :src="dataFooter.logoSrc" class="h-21" />
                            </div>
                            <div class="flex justify-center">
                                <div :class="`text-${dataLayout.footer.colorScheme}-100 border-t border-gray-500 w-10/12`" />
                            </div>

                            <div class="flex justify-center space-x-6">
                                <!-- <div v-for="social in dataFooter.social" group="socialMedia" itemKey="id" :class="[
                                    tool.name === 'grab'
                                        ? 'cursor-grab'
                                        : 'cursor-pointer',
                                    `text-${dataLayout.footer.colorScheme}-400 hover:text-${dataLayout.footer.colorScheme}-500 flex space-x-6`,
                                ]" :disabled="tool.name !== 'grab'">
                                    <template #item="{
                                        element: child,
                                        index: childIndex,
                                    }">
                                        <div>
                                            <span class="sr-only">{{
                                                child.label
                                            }}</span>
                                            <SocialMediaPicker :data="child" @OnDelete="data.social.splice(index, 1)" />
                                        </div>
                                    </template>
                                </div> -->
                            </div>
                        </div>

                        <div class="col-span-2">
                            <!-- <draggable :list="data.columns" group="navigation" itemKey="id" :disabled="tool.name !== 'grab'"
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
                                        <Input :data="element" keyValue="label"
                                            :classCss="`font-bold text-${dataLayout.footer.colorScheme}-500`" />

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
                                                                :cssClass="`text-${dataLayout.footer.colorScheme}-400 pr-2 py-3`"
                                                                @onDelete="() =>
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

                                        <div v-if="element.type == 'description'">
                                            <TextArea :data="element" dataPath="data" />
                                        </div>

                                        <div v-if="element.type == 'info'">
                                            <draggable :list="element.items" group="info" itemKey="name"
                                                :disabled="tool.name !== 'grab'" class="flex flex-col gap-y-2">
                                                <template #item="{
                                                    element: child,
                                                    index: childIndex,
                                                }">
                                                    <div
                                                        class="grid grid-cols-[auto,1fr] gap-4 items-center justify-start gap-y-3 mb-2.5">
                                                        <div v-if="child.type == 'other'"
                                                            :class="`w-full flex items-center justify-center text-${dataLayout.footer.colorScheme}-400 gap-3`">
                                                            <div>
                                                                <IconPicker :key="child.title" :data="child.data" />
                                                            </div>
                                                            <Input :data="child.data" keyValue="label" />
                                                        </div>
                                                        <div v-if="child.type == 'email'"
                                                            :class="`w-full flex items-center justify-center text-${dataLayout.footer.colorScheme}-400 gap-3`">
                                                            <div><font-awesome-icon :icon="['fas', 'envelope']" /></div>
                                                            <Input :data="child" keyValue="data" />
                                                        </div>
                                                        <div v-if="child.type == 'phone'"
                                                            :class="`w-full flex items-center justify-center text-${dataLayout.footer.colorScheme}-400 gap-3`">
                                                            <div><font-awesome-icon :icon="['fas', 'phone']" /></div>
                                                            <Input :data="child" keyValue="data" />
                                                        </div>
                                                    </div>
                                                </template>
                                            </draggable>
                                        </div>
                                    </div>
                                </template>
                            </draggable> -->
                        </div>
                    </div>
                    <div class="mt-16 border-t border-white/10 pt-8 sm:mt-20 lg:mt-24 text-center">
                        <div :class="`text-xs flex justify-center leading-5 text-${dataLayout.footer.colorScheme}-400`">
                            &copy; {{ dataFooter.copyright.startYear }}&nbsp;<span class="font-semibold">
                                <b>{{ dataFooter.copyright.label }}</b>
                            </span>, Inc. All rights reserved.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
</template>