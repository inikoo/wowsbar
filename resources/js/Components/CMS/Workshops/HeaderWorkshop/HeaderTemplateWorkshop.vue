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
import ToolsTopMenu from "@/Components/CMS/Menu/ToolsInTop.vue";
import Header from "@/Components/CMS/Header/index.vue";
import Menu from "@/Components/CMS/Menu/index.vue";
import sideMenuHeader from "@/Components/CMS/Header/Sidebar.vue";
import sideMenu from "@/Components/CMS/Menu/SideMenu.vue"
import { ulid } from 'ulid';
import { notify } from "@kyvg/vue3-notification"
import {
    getDbRef,
    getDataFirebase,
    setDataFirebase,
} from "@/Composables/firebase";

library.add(faHandPointer, faText, faSearch, faImage, faTrash, faBars);
const props = defineProps<{
    data: Object;
    imagesUploadRoute: object;
}>();

console.log('dddd',props)

const headerData = ref(props.data.header);
const menuData = ref(props.data.header.menu);
const selected = ref("menu");
const selectedMenu = ref(null);
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
            if(response.data.thumbnail){
                headerData.value.logo =  response.data.id
                headerData.value.logoSrc =  response.data.thumbnail
            }
        } catch (error) {
            console.log(error);
            notify({
                title: "Failed to Update Banner",
                text: 'Sorry, failed to upload image, due to several reasons',
                type: "error"
            });
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


// async function setToFirebase() {
//     const column = "org/websites/header";
//     try {
//         await setDataFirebase(column, props.data.header);
//     } catch (error) {
//         console.log(error);
//     }
// }

// watch(props.data.header, setToFirebase, { deep: true });

// setToFirebase();


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
                <ToolsTopMenu
                    v-if="selected == 'menu'"
                    :tool="handtools"
                    @changeTheme="changeThemeMenu"
                    :navigation="menuData"
                    :columSelected="selectedMenu"
                    @setColumnSelected="changeNavActive"
                    @addNavigation="addNavigation"
                />
                <div style="transform: scale(0.8)" class="isolate w-full">
                    <Header
                        :theme="headerData.type"
                        :data="headerData"
                        @changeLogo="changeLogo"
                        @click="selected = 'header'"
                        :layout="data.layout.header"
                        class="border-2 "
                        :class="[selected == 'header' ? 'z-20 rounded-sm relative border-gray-500' : 'border-dashed border-gray-300']"
                    />
                    <Menu
                        :theme="menuData.type"
                        :navigation="menuData"
                        :tool="handtools"
                        :layout="data.layout.header"
                        :selectedNav="menuData.items[selectedMenu]"
                        :changeNavActive="changeNavActive"
                        @click="selected = 'menu'"
                        class="border-2"
                        :class="[selected == 'menu' ? 'z-20 rounded-sm relative border-gray-500' : 'border-dashed border-gray-400']"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
