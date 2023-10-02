
  
<script setup lang="ts">
import { onMounted } from "vue"
import 'grapesjs/dist/css/grapes.min.css'
import grapesjs, { usePlugin } from 'grapesjs'
import Basic from 'grapesjs-blocks-basic'
import GrapesForm from 'grapesjs-plugin-forms'
import TailwindComponents from 'grapesjs-tailwind'
import Webpage from 'grapesjs-preset-webpage';
import { ref } from 'vue'
import grapesjsIcons from 'grapesjs-icons'
import { panel } from './Panel'
import { get } from "firebase/database"
import {
    getDbRef,
    setDataFirebase,
} from "@/Composables/firebase";


const data = ref(null)

// const save = async(newData) => {
//   const column = "org/websites/webpages"
//     try {
//         await setDataFirebase(column,newData);
//     } catch (error) {
//         console.error(error);
//     }
// }

const options = {
    collections: [
        'ri',
        'mdi',
        'uim',
        'streamline-emojis'
    ]
}

const escapeName = (name) =>
    `${name}`.trim().replace(/([^a-z0-9\w-:/]+)/gi, "-")

onMounted(() => {
    const editorInstance = grapesjs.init({
        height: "100%",
        container: "#gjs",
        showOffsets: true,
        fromElement: true,
        noticeOnUnload: false,
        selectorManager: { escapeName },
        plugins: [
            Basic,
            GrapesForm,
            TailwindComponents,
            Webpage,
            usePlugin(grapesjsIcons, options)
        ],
        // storageManager: {
        //   type: 'remote',
        //   options: {
        //     remote: {
        //       onStore: (data, editor) => {
        //         const pagesHtml = editor.Pages.getAll().map(page => {
        //           const component = page.getMainComponent();
        //           return {
        //             html: editor.getHtml({ component }),
        //             css: editor.getCss({ component })
        //           }
        //         });
        //         const savedData = { id: 'projectID', data, pagesHtml };
        //         save(savedData);

        //         return savedData;
        //       },
        //       onLoad: async(result) => {
        //         const snapshot = await get(getDbRef("org/websites/webpages"))
        //         const firebaseData = snapshot.exists() ? snapshot.val() : null
        //         result.data = firebaseData.data
        //         console.log('inii',firebaseData, result)
        //         return firebaseData.data
        //       },
        //     }
        //   },
        // },
    });

    console.log(editorInstance.BlockManager)

    editorInstance.BlockManager.get('icon').attributes = { ...editorInstance.BlockManager.get('icon').attributes, media: '<svg xmlns="http://www.w3.org/2000/svg"  viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. --><path d="M116.65 219.35a15.68 15.68 0 0 0 22.65 0l96.75-99.83c28.15-29 26.5-77.1-4.91-103.88C203.75-7.7 163-3.5 137.86 22.44L128 32.58l-9.85-10.14C93.05-3.5 52.25-7.7 24.86 15.64c-31.41 26.78-33 74.85-5 103.88zm143.92 100.49h-48l-7.08-14.24a27.39 27.39 0 0 0-25.66-17.78h-71.71a27.39 27.39 0 0 0-25.66 17.78l-7 14.24h-48A27.45 27.45 0 0 0 0 347.3v137.25A27.44 27.44 0 0 0 27.43 512h233.14A27.45 27.45 0 0 0 288 484.55V347.3a27.45 27.45 0 0 0-27.43-27.46zM144 468a52 52 0 1 1 52-52 52 52 0 0 1-52 52zm355.4-115.9h-60.58l22.36-50.75c2.1-6.65-3.93-13.21-12.18-13.21h-75.59c-6.3 0-11.66 3.9-12.5 9.1l-16.8 106.93c-1 6.3 4.88 11.89 12.5 11.89h62.31l-24.2 83c-1.89 6.65 4.2 12.9 12.23 12.9a13.26 13.26 0 0 0 10.92-5.25l92.4-138.91c4.88-6.91-1.16-15.7-10.87-15.7zM478.08.33L329.51 23.17C314.87 25.42 304 38.92 304 54.83V161.6a83.25 83.25 0 0 0-16-1.7c-35.35 0-64 21.48-64 48s28.65 48 64 48c35.2 0 63.73-21.32 64-47.66V99.66l112-17.22v47.18a83.25 83.25 0 0 0-16-1.7c-35.35 0-64 21.48-64 48s28.65 48 64 48c35.2 0 63.73-21.32 64-47.66V32c0-19.48-16-34.42-33.92-31.67z"/></svg>' }

});
</script> 

<template>
    <div id="gjs"></div>
</template>
  
<style lang="scss">
.panel {
    width: 90%;
    max-width: 700px;
    border-radius: 3px;
    padding: 30px 20px;
    margin: 150px auto 0px;
    background-color: #d983a6;
    box-shadow: 0px 3px 10px 0px rgba(0, 0, 0, 0.25);
    color: rgba(255, 255, 255, 0.75);
    font: caption;
    font-weight: 100;
}

.gjs-pn-panel {
    padding: -10px;
}

.gjs-block svg {
    width: 100%;
}

.change-theme-button {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin: 5px;
}

.change-theme-button:focus {
    /* background-color: yellow; */
    outline: none;
    box-shadow: 0 0 0 2pt #c5c5c575;
}

#gjs {
    border: none;
}

.gjs-one-bg {
    background-color: #f9fafb !important;
}

.gjs-three-bg {
    background-color: rgb(107 114 128) !important;
    @apply text-gray-100
}

.gjs-two-color {
    color: rgb(75 85 99) !important;
}

.gjs-clm-header-label {
    @apply text-gray-600
}

// Layer: active state
.gjs-layer.gjs-selected .gjs-layer-title {
    @apply bg-gray-300 hover:bg-gray-200
}

// Layer: on hover
.gjs-hovered {
    background-color: rgb(0, 94, 255) !important;
}

// Panel: Button
.gjs-pn-btn {
    @apply hover:ring-1 hover:ring-gray-400 text-gray-500;

    &.gjs-pn-active {
        @apply shadow-none bg-gray-300 ring-1 ring-gray-400 text-gray-600 hover:text-gray-700
    }
}

.gjs-four-color-h:hover {
    @apply hover:text-gray-600
}

// Box in select Blocks
.gjs-block {
    @apply bg-gray-200 text-gray-500 hover:text-gray-600 hover:ring-2 hover:ring-gray-500 transition-all duration-100 ease-in-out
}

// Head 'Style Manager'
.gjs-sm-sector-title, .gjs-block-category {
    @apply bg-gray-600 hover:bg-gray-500 text-white
}

// Content on expand in Style Manager
.gjs-sm-properties, .gjs-blocks-c {
    @apply bg-gray-100 text-gray-600 pb-2
}

// Icon (+) on add style
.gjs-sm-stack #gjs-sm-add {
    @apply text-gray-500
}

.gjs-field input:focus {
    @apply text-gray-600
}

#gjs-clm-states {
    @apply text-xs
}

.gjs-field {
    @apply bg-gray-300 text-gray-700;

    &input#gjs-clm-new {
        @apply text-gray-500
    }
}

.gjs-clm-tags #gjs-clm-new {
    @apply text-gray-600
}
</style>