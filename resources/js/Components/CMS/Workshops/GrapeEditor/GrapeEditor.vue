
  
<script setup lang="ts">
import { onMounted } from "vue"
import 'grapesjs/dist/css/grapes.min.css'
import grapesjs from 'grapesjs'
import Basic from 'grapesjs-blocks-basic'
import GrapesForm from 'grapesjs-plugin-forms'
import TailwindComponents from 'grapesjs-tailwind'
import Webpage from 'grapesjs-preset-webpage';
import { ref } from 'vue'
import { panel } from './Panel'
import {  get } from "firebase/database"
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
      Webpage
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