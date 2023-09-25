<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Tue, 22 Aug 2023 19:44:06 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup lang="ts">
import { ref, reactive, watch } from "vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import {
    faHandPointer,
    faText,
    faSearch,
    faImage,
    faTrash,
    faBars,
} from "@/../private/pro-solid-svg-icons";
import ToolsTopHeader from "@/Components/CMS/Header/ToolsTop.vue";
import ToolTopMenu from "@/Components/CMS/Menu/ToolsInTop.vue";
import Header from "@/Components/CMS/Header/index.vue";
import Menu from "@/Components/CMS/Menu/index.vue";
import sideMenuHeader from "@/Components/CMS/Header/Sidebar.vue";
import sideMenu from "@/Components/CMS/Menu/SideMenu.vue"
import { ulid } from 'ulid';

library.add(faHandPointer, faText, faSearch, faImage, faTrash, faBars);
const props = defineProps<{
    data: Object;
    imagesUploadRoute: object;
}>();

const headerData = ref({ ...props.data.header });
const menuData = ref({ ...props.data.header.menu });
const selected = ref("menu");
const selectedMenu = ref(0);
const handtools = ref({ name: "edit", icon: ["fas", "fa-hand-pointer"] });

const changeLogo = async (element) => {
    const file = element.target.files[0];
    if (file) {
        try {
            const response = await axios.post(
                route(
                    props.imagesUploadRoute.name,
                    props.imagesUploadRoute.parameters
                ),
                { images: file },
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            );
            console.log(response);
        } catch (error) {
            console.log(error);
        }
    }
};


const changeThemeHeader = (value) => {
    headerData.value.type = value;
};

const changeThemeMenu = (value) => {
    menuData.value.type = value;
};

const changeNavActive = (value) => {
    selectedMenu.value = value;
};

const addNavigation=()=>{
    menuData.value.items.push(
        {
            label: 'New Menu',
            id: ulid(),
            icon: 'far fa-dot-circle',
            type: 'link',
            href: '#',
        }
    )
}
</script>

<template>
    <div class="bg-white">
        <div class="flex">
            <sideMenuHeader  v-if="selected == 'header'" :data="headerData" @changeLogo="changeLogo" />
            <sideMenu v-if="selected == 'menu'" :data="menuData" :selectedMenu="selectedMenu" />
            <!-- editing area -->
            <div class="w-full bg-gray-200 overflow-hidden">
                <ToolsTopHeader
                    v-if="selected == 'header'"
                    :theme="headerData.type"
                    @changeTheme="changeThemeHeader"
                    @click="(e) => e.stopPropagation()"
                />
                <ToolTopMenu
                    v-if="selected == 'menu'"
                    :tool="handtools"
                    @changeTheme="changeThemeMenu"
                    :navigation="menuData"
                    :columSelected="selectedMenu"
                    @setColumnSelected="changeNavActive"
                    @addNavigation="addNavigation"
                />
                <div style="transform: scale(0.8)" class="w-full">
                    <Header
                        :theme="headerData.type"
                        :data="headerData"
                        @changeLogo="changeLogo"
                        @click="selected = 'header'"
                    />
                    <Menu
                        :theme="menuData.type"
                        :navigation="menuData"
                        :tool="handtools"
                        :selectedNav="menuData.items[selectedMenu]"
                        :changeNavActive="changeNavActive"
                        @click="selected = 'menu'"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
