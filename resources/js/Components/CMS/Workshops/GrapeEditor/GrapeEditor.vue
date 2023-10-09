<script setup lang="ts">
import { onMounted, ref } from "vue";
import "grapesjs/dist/css/grapes.min.css";
import grapesjs, { usePlugin } from "grapesjs";
import Webpage from "grapesjs-preset-webpage";
import { router } from '@inertiajs/vue3'
import Basic from "grapesjs-blocks-basic";
import grapesjsIcons from "grapesjs-icons";
import { CustomBlock } from "@/Components/CMS/Workshops/GrapeEditor/CustomBlocks/CustomBlock";
import axios from "axios"


const emits = defineEmits(['onSaveToServer']);
const props = defineProps<{
    plugins: Array;
    customBlocks?: Array;
    updateRoute?: Object;
    loadRoute?: Object;
    imagesUploadRoute?:Object
}>();

const editorInstance = ref(null);
const options = { collections: ["ri", "mdi", "uim", "streamline-emojis"]};

const deleteImageStore = (data) => {
    const allImages = data.assets;
    const usageImages = editorInstance.value.DomComponents.getWrapper().find('img');
    allImages.forEach((image,index) => {
        usageImages.find((item) => console.log(item.attributes.src,image.src) )
        const hasImages = usageImages.find((item) => item.attributes.src == image.src)
        if (!hasImages) {
            allImages.splice(index,1)
            editorInstance.value.AssetManager.remove(image)
        }
    });
};
 
const Store = async (data, editor) => {
    const pagesHtml = editor.Pages.getAll().map(page => {
        const component = page.getMainComponent();
        return {
            html: editor.getHtml({ component }),
            css: editor.getCss({ component })
        }
    });
    deleteImageStore(data)
    try {
        const response = await axios.post(
            route(
                props.updateRoute.name,
                props.updateRoute.parameters
            ),
            { data, pagesHtml },
        )
        emits('onSaveToServer', response?.data?.isDirty)
        console.log('saving......')
        
    } catch (error) {
        console.log(error)
    }
}

const Load = async (data) => {
    try {
        const response = await axios.get(
            route(
                props.loadRoute.name,
                props.loadRoute.parameters
            ),
        )
        if (response) {
           return response.data.src
        }
    } catch (error) {
        console.log(error)
    }
}





onMounted(() => {
    editorInstance.value = grapesjs.init({
        height: "100%",
        container: "#gjs",
        showOffsets: true,
        fromElement: true,
        noticeOnUnload: false,
        plugins: [Webpage, Basic, usePlugin(grapesjsIcons, options),...props.plugins],
        canvas: {
            // styles: ['https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css']
            scripts:['https://cdn.tailwindcss.com']
        },
        storageManager: {
            type: 'remote',
        },
        assetManager: {
            storeAfterUpload  : false,
            uploadFile: async function (e) {
                var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                console.log('test',files)
             try {
             const response = await axios.post(
                route(
                    props.imagesUploadRoute.name,
                    props.imagesUploadRoute.parameters
                ),
                { images: files },
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            );
                for(const image of response.data.data){
                    let imageToStore = 
                        {
                        src: image.thumbnail.original,
                        type: 'image',
                        id: image.id,
                        name : image.slug
                }
                editorInstance.value.AssetManager.add(imageToStore);
            }
              
                } catch (error) {
                    console.log(error)
                }
                },
            }

    });
    editorInstance.value.Storage.add('remote', {
        async load() {
          
            return Load()
        },
        async store(data) { return Store(data, editorInstance.value) },
    });
    CustomBlock(editorInstance.value)
});
</script>

<template>
    <div id="gjs">
    <slot name="defaultComponents"></slot>
    </div>
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
    @apply text-gray-100;
}

.gjs-two-color {
    color: rgb(75 85 99) !important;
}

.gjs-clm-header-label {
    @apply text-gray-600;
}

// Layer: active state
.gjs-layer.gjs-selected .gjs-layer-title {
    @apply bg-gray-300 hover:bg-gray-200;
}

// Layer: on hover
.gjs-hovered {
    background-color: rgb(0, 94, 255) !important;
}

// Panel: Button
.gjs-pn-btn {
    @apply hover:ring-1 hover:ring-gray-400 text-gray-500;

    &.gjs-pn-active {
        @apply shadow-none bg-gray-300 ring-1 ring-gray-400 text-gray-600 hover:text-gray-700;
    }
}

.gjs-four-color-h:hover {
    @apply hover:text-gray-600;
}

// Box in select Blocks
.gjs-block {
    @apply bg-gray-200 text-gray-500 hover:text-gray-600 hover:ring-2 hover:ring-gray-500 transition-all duration-100 ease-in-out;
}

// Head 'Style Manager'
.gjs-sm-sector-title,
.gjs-block-category {
    @apply bg-gray-600 hover:bg-gray-500 text-white;
}

// Content on expand in Style Manager
.gjs-sm-properties,
.gjs-blocks-c {
    @apply bg-gray-100 text-gray-600 pb-2;
}

// Icon (+) on add style
.gjs-sm-stack #gjs-sm-add {
    @apply text-gray-500;
}

.gjs-field input:focus {
    @apply text-gray-600;
}

#gjs-clm-states {
    @apply text-xs;
}

.gjs-field {
    @apply bg-gray-300 text-gray-700;

    &input#gjs-clm-new {
        @apply text-gray-500;
    }
}

.gjs-clm-tags #gjs-clm-new {
    @apply text-gray-600;
}
</style>
