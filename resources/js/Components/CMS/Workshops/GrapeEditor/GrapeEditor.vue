
  
<script setup lang="ts">
import { onMounted } from "vue"
import 'grapesjs/dist/css/grapes.min.css'
import grapesjs from 'grapesjs'
import Basic from 'grapesjs-blocks-basic'
import GrapesForm from 'grapesjs-plugin-forms'
import TailwindComponents from 'grapesjs-tailwind'
import { ref } from 'vue'

console.log(TailwindComponents)

const data = ref(null)

const save = (newData) => {
    console.log(newData)
    data.value = newData
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
        storageManager: false,
        selectorManager: { escapeName },
        plugins: [
            Basic,
            GrapesForm,
            TailwindComponents
        ],
        // storageManager: {
        //   type: 'remote',
        //   options: {
        //     remote: {
        //       onStore: (data, editor) => {
        //         const pagesHtml = editor.Pages.getAll().map(page => {
        //           const component = page.getMainComponent()
        //           return {
        //             html: editor.getHtml({ component }),
        //             css: editor.getCss({ component })
        //           }
        //         })
        //         const savedData = { id: 'projectID', data, pagesHtml };

        //         // Call the save function here with the saved data
        //         save(savedData);

        //         return savedData;
        //       },
        //       onLoad: result => result.data,
        //     }
        //   },
        // },
    });

    // editorInstance.Panels.addButton("options", {
    //   id: "update-theme",
    //   className: "fa fa-adjust",
    //   command: "open-update-theme",
    //   attributes: {
    //     title: "Update Theme",
    //     "data-tooltip-pos": "bottom"
    //   }
    // });

    editorInstance.addComponents('<div class="cls">New coddddddddddddddddddddddddddmponent</div>');
});



</script> 

<template>
    <div id="gjs" class="p-4"></div>
    <div id="blocks"></div>
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

/* We can remove the border we've set at the beginning */
#gjs {
    border: none;
}

/* Primary color for the background */
.gjs-one-bg {
    background-color: #f9fafb;
}

/* Secondary color for the text color */
.gjs-two-color {
    @apply text-gray-600
}

/* Tertiary color for the background */
.gjs-three-bg {
    background-color: #f9fafb;
    color: #3b3b3b;
}


// Panel: Button
.gjs-pn-btn {
    @apply hover:ring-1 hover:ring-gray-400;
    
    &.gjs-pn-active {
        @apply shadow-none bg-gray-300 ring-1 ring-gray-400 text-gray-600 hover:text-gray-700
    }
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
</style>