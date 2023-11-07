<script setup lang="ts">
/* import CKEDITOR from 'ckeditor/ckeditor' */
import { onMounted, ref } from "vue";
import "grapesjs/dist/css/grapes.min.css";
import grapesjs, { usePlugin } from "grapesjs";
import axios from "axios"
import 'grapesjs-component-code-editor/dist/grapesjs-component-code-editor.min.css';
import { notify } from "@kyvg/vue3-notification"
import grapesJSMJML from 'grapesjs-mjml'
import CkeEditor from 'grapesjs-plugin-ckeditor'
/* import type CKE from 'ckeditor4'; */

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

/* console.log(CKE) */

onMounted(() => {
    editorInstance.value = grapesjs.init({
        container: "#gjs",
        showOffsets: true,
        fromElement: true,
        noticeOnUnload: false,
        plugins: [grapesJSMJML, CkeEditor],
        pluginsOpts: {
            [grapesJSMJML]: {
                blocks: ['mj-1-column', 'mj-2-columns', 'mj-3-columns', 'mj-text', 'mj-button', 'mj-image', 'mj-divider', 'mj-social-group',
                    'mj-social-element', 'mj-spacer', 'mj-navbar', 'mj-navbar-link', 'mj-hero', 'mj-wrapper', 'mj-raw'],
            },
            [CkeEditor]: {
                options: {
                    language: 'en',
                    startupFocus: true,
                    extraAllowedContent: '*(*);*{*}', 
                    allowedContent: true,
                    uiColor: '#222f3e',
                    extraPlugins: `justify,colorbutton,panelbutton,font,sourcedialog,showblocks`,
                    toolbarGroups : [
                            { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                            { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                            { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                            { name: 'forms', groups: [ 'forms' ] },
                            { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                            { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                            { name: 'links', groups: [ 'links' ] },
                            { name: 'insert', groups: [ 'insert' ] },
                            { name: 'styles', groups: [ 'styles' ] },
                            { name: 'colors', groups: [ 'colors' ] },
                            { name: 'tools', groups: [ 'tools' ] },
                            { name: 'others', groups: [ 'others' ] },
                            { name: 'about', groups: [ 'about' ] }
                        ],
                        removeButtons : 'Cut,Copy,Paste,PasteText,PasteFromWord,Source,Save,Templates,NewPage,ExportPdf,Preview,Print,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,CopyFormatting,CreateDiv,Image,Table,Smiley,SpecialChar,PageBreak,Iframe,Styles,About,Maximize,ShowBlocks'
                },
                position: 'left',
            }

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
});


</script>


<template>
    <div class="editor">
        <div id="gjs">
            <slot name="defaultComponents"></slot>
        </div>
    </div>
</template>

<style lang="scss">
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
</style>