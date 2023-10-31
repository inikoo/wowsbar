<script setup lang="ts">
import { onMounted, ref } from "vue";
import "grapesjs/dist/css/grapes.min.css";
import grapesjs, { usePlugin } from "grapesjs";
import axios from "axios"
import CustomLayout  from '@/Components/CMS/Workshops/GrapeEditor/CustomLayout/index.ts'
import CKEeditor from 'grapesjs-plugin-ckeditor'
import 'grapesjs-component-code-editor/dist/grapesjs-component-code-editor.min.css';


const emits = defineEmits(['onSaveToServer']);
const props = withDefaults(defineProps<{
    plugins: Array;
    customBlocks?: Array;
    updateRoute?: Object;
    loadRoute?: Object;
    imagesUploadRoute?:Object
    useBasic?:Boolean
}>(),{
    useBasic: true,
});



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
            css: editor.getCss({ component }),
            js:editor.getJs({ component })
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
        container: "#gjs",
        showOffsets: true,
        fromElement: true,
        noticeOnUnload: false,
        plugins: [...props.plugins, CustomLayout, CKEeditor],
        colorPicker: { appendTo: 'parent',   offset: { top: 26, left: -166, }}, 
        canvas: {
            styles: ['https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' ],
            scripts:['https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js']
        },
        storageManager: {
            type: 'remote',
        },
        assetManager: {
         // custom: true,
            storeAfterUpload  : false,
            uploadFile: async function (e) {
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
                for(const image of response.data.data){
                    let imageToStore = 
                        {
                        src: image.source.original,
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

.gjs-pn-buttons {
    align-items: center;
    display: flex;
    justify-content: start ;
}

.gjs-pn-btn{
    font-size: 14px;
}
.gjs-pn-btn.gjs-pn-active {
    background-color: rgba(0,0,0,.15);
    box-shadow: 0 0 3px rgba(0,0,0,.25) inset;
    padding: 5px;
    border-radius: 5px 5px 0px 0px;
}
.gjs-pn-views {
    max-height: 36px;
}
.gjs-pn-panel {
    padding: 10px 5px 0px 5px;

}

.gjs-pn-views-container {
    box-shadow: initial;
    border-top: 2px solid rgba(0,0,0,0.2);
    top: 40px;
    padding-top: 0;
    height: calc(100% - 40px);
    background: #D9D9D9;
}

.gjs-block {
    width: 28%;
}
</style>