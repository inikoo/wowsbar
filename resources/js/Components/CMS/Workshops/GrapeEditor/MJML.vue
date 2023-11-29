<script setup lang="ts">
/* import CKEDITOR from 'ckeditor/ckeditor' */
import { onMounted, ref } from "vue";
import "grapesjs/dist/css/grapes.min.css";
import grapesjs, { usePlugin } from "grapesjs";
import axios from "axios"
import 'grapesjs-component-code-editor/dist/grapesjs-component-code-editor.min.css';
import { notify } from "@kyvg/vue3-notification"
import grapesJSMJML from 'grapesjs-mjml'
import RTE from '@/Components/CMS/Workshops/GrapeEditor/CustomLayout/Rte/Rte.ts'

const emits = defineEmits(['onSaveToServer']);
const props = withDefaults(defineProps<{
    plugins?: Array;
    customBlocks?: Array;
    updateRoute?: Object;
    loadRoute?: Object;
    imagesUploadRoute?: Object
    useBasic?: Boolean
}>(), {
    useBasic: true,
});

const editorInstance = ref(null);
const deleteImageStore = (data) => {
    const allImages = data.assets;
    const usageImages = editorInstance.value.DomComponents.getWrapper().find('img');
    allImages.forEach((image, index) => {
        usageImages.find((item) => console.log(item.attributes.src, image.src))
        const hasImages = usageImages.find((item) => item.attributes.src == image.src)
        if (!hasImages) {
            allImages.splice(index, 1)
            editorInstance.value.AssetManager.remove(image)
        }
    });
};

const Store = async (data, editor) => {
    const pagesHtml = editor.Pages.getAll().map(page => {
        const component = page.getMainComponent();
        return {
            html: editor.getHtml({ component }),
            css: editor.getCss({ component }),
            js: editor.getJs({ component })
        }
    });
    deleteImageStore(data)
    try {
        const response = await axios.post(
            route(
                props.updateRoute.name,
                props.updateRoute.parameters
            ),
            { data, pagesHtml, },
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

const uploadFile = async (e) => {
    var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
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
        for (const image of response.data.data) {
            let imageToStore =
            {
                src: image.source.original,
                type: 'image',
                id: image.id,
                name: image.slug
            }
            editorInstance.value.AssetManager.add(imageToStore);
        }

    } catch (error) {
        notify({
            title: "Failed to update banner",
            text: error,
            type: "error"
        });
    }
}

/* console.log(ClassicEditor) */

onMounted(() => {
    editorInstance.value = grapesjs.init({
        container: "#gjs",
        showOffsets: false,
        fromElement: false,
        noticeOnUnload: false,
        plugins: [grapesJSMJML, RTE ],
        pluginsOpts: {
            [grapesJSMJML]: {
                blocks: ['mj-1-column', 'mj-2-columns', 'mj-3-columns', 'mj-text', 'mj-button', 'mj-image', 'mj-divider', 'mj-social-group',
                    'mj-social-element', 'mj-spacer', 'mj-navbar', 'mj-navbar-link', 'mj-hero', 'mj-wrapper', 'mj-raw'],
                useCustomTheme : true
            },
        },
        colorPicker: { appendTo: 'parent', offset: { top: 26, left: -166, } },
        assetManager: {
            // custom: true,
            storeAfterUpload: false,
            uploadFile: uploadFile
        },
        storageManager: {
            type: 'remote'
        },
    });
    editorInstance.value.Storage.add('remote', {
        async load() { return Load() },
        async store(data) { return Store(data, editorInstance.value) }
    });
    editorInstance.value.on('load', () => {
        const blockBtn = editorInstance.value.Panels.getButton('views', 'open-blocks');
        blockBtn.set('active', 1);
    })
});


</script>


<template>
    <div class="editor">
        <div id="gjs">
            <slot name="defaultComponents"></slot>
        </div>
    </div>
</template>

<style>
.gjs-rte-toolbar {
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3), 0 1px 2px rgba(0, 0, 0, 0.44);
    border-radius: 3px;
}

.gjs-rte-action {
    font-size: 1rem;
    border-right: none;
    padding: 10px;
    min-width: 35px;
}

.gjs-rte-actionbar {
    max-width: 600px;
    flex-wrap: wrap;
}

.rte-hilite-btn {
    padding: 3px 6px;
    border-radius: 3px;
    background: rgba(210, 120, 201, 0.3);
}

.gjs-field {
    padding: 5px;
}

/* picker */
.rte-color-picker {
    display: none;
    padding: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2), 0 2px 5px rgba(0, 0, 0, 0.34);
    border-radius: 5px;
    position: absolute;
    top: 55px;
    width: 250px;
    transition: all 2s ease;


    &:before {
        content: "";
        position: absolute;
        top: -20px;
        left: 46%;
        border-width: 10px;
        border-style: solid;
    }

    &.dark {
        background: rgba(0, 0, 0, 0.80);
        color: white;

        &:before {
            border-color: transparent transparent rgba(0, 0, 0, 0.75) transparent;
        }
    }

    &.light {
        background: rgba(255, 255, 255, 0.75);

        &:before {
            border-color: transparent transparent rgba(255, 255, 255, 0.75) transparent;
        }
    }

    &>div {
        width: 30px;
        display: inline-block;
        height: 30px;
        margin: 5px;
        border-radius: 100%;
        opacity: 0.7;

        &:hover {
            opacity: 1;
        }
    }
}

.picker-wrapper {
    padding: 20px;
}

.gjs-rte-action {
    font-size: 1rem;
    border-right: none;
    padding: 10px;
    min-width: fit-content;
}



.custom-tag-button {
    .cke_button_icon {
        display: none;
    }

    .cke_button_label {
        display: inline;
    }
}


/* //change-theme-button */
.gjs-cv-canvas {
    box-sizing: border-box;
    width: 80%;
    height: calc(100% - 40px);
    bottom: 0;
    margin: auto;
    overflow: hidden;
    z-index: 1;
    position: absolute;
    left: 35px;
    top: 70px;
}

.editor{
    width: 100%;
    height: calc(100% - 4rem);
}

.gjs-pn-views{
    background: #F3F4F6 !important;
}

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


.gjs-layer.gjs-selected .gjs-layer-title {
    @apply bg-gray-300 hover:bg-gray-200;
}


.gjs-hovered {
    background-color: rgb(0, 94, 255) !important;
}


.gjs-pn-btn {
    @apply hover:ring-1 hover:ring-gray-400 text-gray-500;

    &.gjs-pn-active {
        @apply shadow-none bg-gray-300 ring-1 ring-gray-400 text-gray-600 hover:text-gray-700;
    }
}

.gjs-four-color-h:hover {
    @apply hover:text-gray-600;
}


.gjs-block {
    @apply bg-gray-200 text-gray-500 hover:text-gray-600 hover:ring-2 hover:ring-gray-500 transition-all duration-100 ease-in-out;
}


.gjs-sm-sector-title,
.gjs-block-category {
    @apply bg-gray-600 hover:bg-gray-500 text-white;
}


.gjs-sm-properties,
.gjs-blocks-c {
    @apply bg-gray-100 text-gray-600 pb-2;
}


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

.grp-wrapper {
  background-image: url("data:image/png:base64,iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAIAAADZF8uwAAAAGUlEQVQYV2M4gwH+YwCGIasIUwhT25BVBADtzYNYrHvv4gAAAABJRU5ErkJggg==");
}
.grp-preview {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  cursor: crosshair;
}
.grp-handler {
  width: 4px;
  margin-left: -2px;
  user-select: none;
  height: 100%;
  -webkit-user-select: none;
  -moz-user-select: none;
}

.grp-handler-close {
  color: rgba(0, 0, 0, 0.4);
  border-radius: 0 2px 10px rgba(0, 0, 0, 0.25);
  background-color: #fff;
  text-align: center;
  width: 15px;
  height: 15px;
  margin-left: -5px;
  line-height: 10px;
  font-size: 21px;
  cursor: pointer;
}

.grp-handler-close {
  position: absolute;
  top: -17px;
}

.grp-handler-drag {
  background-color: rgba(0, 0, 0, 0.5);
  cursor: col-resize;
  width: 100%;
  height: 100%;
}

.grp-handler-selected .grap-handler-drag {
  background-color: rgba(255, 255, 255, 0.5);
}

.grp-handler-cp-c {
  display: none;
}

.grp-handler-selected .grp-handler-cp-c {
  display: block;
}

.grp-handler-cp-wrap {
  width: 15px;
  height: 15px;
  margin-left: -8px;
  border: 3px solid #fff;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.25);
  overflow: hidden;
  border-radius: 100%;
  cursor: pointer;
}

.grp-handler-cp-wrap input[type="color"] {
  opacity: 0;
  cursor: pointer;
}


.gjs-frame a {
    /* color: #0d6efd; */
    background-color: red !important;
    text-decoration: none !important;
}


</style>