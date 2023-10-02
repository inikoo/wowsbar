<script setup lang="ts">
import { ref } from "vue";

import draggable from "vuedraggable";
import HyperLink from "@/Components/CMS/Fields/Hyperlink.vue";
import SubMenu from "../SubMenu.vue";
import { faUser, faHeart, faShoppingCart, faSignOut } from '../../../../../private/pro-solid-svg-icons';
import { library } from "@fortawesome/fontawesome-svg-core";
import { get } from "lodash";
import IconPicker from "@/Components/CMS/Fields/IconPicker/IconPicker.vue";
library.add(faUser, faHeart, faShoppingCart, faSignOut)

import { defineProps } from "vue";

const props = defineProps({
    navigation: {
        type: Object,
        required: true,
    },
    tool: {
        type: Object,
        required: true,
    },
    selectedNav: {
        type: Object,
        required: false,
    },
    changeNavActive: {
        type: Function,
        required: true,
    },
    layout : Object
});
const openNav = ref(null);
</script>

<template>
    <div :class="`bg-${layout.colorScheme}-500 relative`">
        <div aria-hidden="true" class="absolute inset-0 bg-gray-900 opacity-50"/>
        <header class="relative z-10">
            <nav aria-label="Top">
                <!-- navigation -->
                <div class="bg-gray-600 bg-opacity-10">
                    <div class="mx-auto px-4 sm:px-6 lg:px-4">
                            <div class="flex h-16 items-center justify-between">
                                <div class="h-full flex">
                                    <draggable
                                        v-model="navigation.items"
                                        group="topMenu"
                                        options="id"
                                        :disabled="tool.name !== 'grab'"
                                        class="flex justify-center space-x-8 h-fit"
                                        itemKey="id"
                                    >
                                        <!-- Navigation -->
                                        <template v-slot:item="{element: category,index}">
                                            <div :class="[
                                                    get(selectedNav,'id') == category.id? 'outline outline-gray-400': '',
                                                    tool.name !== 'grab' ? 'cursor-pointer': 'cursor-grab',
                                                ]"
                                            >
                                              <!-- Flyout menus -->
                                                <div v-if="category.type =='group'" :key="category.id" class="flex relative">
                                                    <div @click="() => {(openNav =category.id),changeNavActive(index);}"
                                                        class="py-5 px-2.5 relative z-10 items-center justify-center text-sm font-medium text-gray-200 transition-colors duration-200 ease-out"
                                                    >
                                                        <div class="flex gap-3">
                                                            <IconPicker :key="category.id" :data="category" class="text-gray-200"/>
                                                            <HyperLink
                                                                :formList="{
                                                                    label: 'label',
                                                                }"
                                                                :useDelete="true"
                                                                :data="category"
                                                                label="label"
                                                                @OnDelete="()=>{navigation.categories.splice(index,1)}"
                                                                cssClass="text-left text-sm font-medium text-gray whitespace-nowrap"
                                                            />
                                                        </div>
                                                    </div>

                                                    <!-- Popup: Navigation -->
                                                    <div v-if="openNav == category.id">
                                                        <SubMenu 
                                                            :data="category" 
                                                            @OnClose="() => {changeNavActive(null),(openNav = null)}"
                                                            :tool="tool"
                                                        />
                                                    </div>
                                                </div>
                                                  <!-- Flyout menus -->
                                                  <!-- menus -->
                                                <div v-if="category.type == 'link'" class="py-5 px-2.5 leading-4"
                                                    @click="(e) => {changeNavActive(index),(openNav =null);}"
                                                >
                                                <div class="flex gap-3">
                                                  <IconPicker :key="category.id" :data="category" class="text-gray-200"/>
                                                <HyperLink
                                                      :formList="{
                                                          label: 'label',
                                                          link: 'link',
                                                      }"
                                                      :useDelete="true"
                                                      :data="category"
                                                      label="label"
                                                      @OnDelete="()=>{navigation.categories.splice(index,1)}"
                                                      cssClass="items-center text-sm font-medium text-gray-200 whitespace-nowrap"
                                                  />
                                                </div>
                                                </div>
                                              
                                                  <!-- menus -->
                                            </div>
                                        </template>
                                    </draggable>
                                </div>
                            </div>
                      
                    </div>
                </div>
            </nav>
        </header>
    </div>
</template>

<style>
:focus-visible {
    outline: -webkit-focus-ring-color auto 0px;
}
</style>
