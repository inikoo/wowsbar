<script setup lang="ts">
import { onMounted, defineExpose, ref } from "vue";
import { loadScript, getNextEditorId, useUnlayer } from "./script-loader";
import axios from "axios"
import { notify } from "@kyvg/vue3-notification";

const props = withDefaults(defineProps<{
    updateRoute?: Object;
    loadRoute?: Object;
    imagesUploadRoute?: Object
    mailshot?: object
}>(), {});

const emits = defineEmits(['onSaveToServer']);
const { isLoaded, isLoading } = useUnlayer();
// get last editor id
const editorId = getNextEditorId();
// variable untuk menyimpan instance editor
let editor = null;
const editorRef = ref(null);
const loadingReady = ref(true)


// on mount load editor unlayer

const Store = async (update, data) => {
    try {
        const response = await axios.post(
            route(
                props.updateRoute.name,
                props.updateRoute.parameters
            ),
            { data: update, pagesHtml: { ...data } },
        )
         emits('onSaveToServer', response?.data?.isDirty)
    } catch (error) {
        console.log(error)
    }
}

const Load = async () => {
    try {
        const response = await axios.get(
            route(
                props.loadRoute.name,
                props.loadRoute.parameters
            ),
        )
        if (response) {
            // console.log(response)
            return response.data.html.design
        }
    } catch (error) {
        console.log(error)
        notify({
            title: "Failed",
            text: "failed to get data",
            type: "error",
        });
    }
}

const setToNewTemplate=(template)=>{
    editor.loadDesign(template)
}

const getMergeTagData=()=>{
    return axios.get(route('org.json.mailshot.merge-tags', { id: props.mailshot.id }))
        .then(response => response.data)
        .catch(error => {
            console.error(error);
            return [];
        });
}



onMounted(async () => {
    //loadeditor
    await loadScript();

    const opt = {
        id: editorId,
        displayMode: 'email',
        features: {
            sendTestEmail: true
        },
        mergeTags: await getMergeTagData(),
        tools: {
            form: {
                enabled: false
            },
            menu: {
                enabled: true
            },
            divider: {
                enabled: true
            },
        },
        fonts: {
            showDefaultFonts: true,
            customFonts: [
                {
                    label: "Comic Sans",
                    value: "'Comic Sans MS', cursive, sans-serif"
                },
                {
                    label: "Lobster Two",
                    value: "'Lobster Two',cursive",
                    url: "https://fonts.googleapis.com/css?family=Lobster+Two:400,700"
                },
                {
                    label: "Roboto",
                    value: "Roboto",
                    url: "https://fonts.googleapis.com/css2?family=Roboto:ital,wght@1,100&display=swap"
                },
                {
                    label: "Montserrat",
                    value: "Montserrat",
                    url: "https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@1,100&display=swap" 
                }
            ]
        }
        // other options for the editor can be added here if needed
    };

    // unlayer adalah global object yang dibuat oleh embed.js
    editor = unlayer.createEditor(opt);

    //autosave
    editor.addEventListener('design:updated', function (updates) {
        editor.exportHtml(function (data) {
            Store(updates, data)
        })
    })

    //onready
    editor.addEventListener('editor:ready', function () {
        loadingReady.value = false
    });

    //loadData
    const load = await Load();
    editor.loadDesign(load);

    //uploadImage
    editor.registerCallback('image', async function (file, done) {
        try {
            const response = await axios.post(
                route(
                    props.imagesUploadRoute.name,
                    props.imagesUploadRoute.parameters
                ),
                { images: file.attachments },
                {
                    headers: { "Content-Type": "multipart/form-data" },
                }
            );
            for (const image of response.data.data) {
                done({ progress: 100, url: image.source.original })
            }


        } catch (error) {
            console.log(error)
        }
    })

    //bodyUnlayer
    editor.setBodyValues({
        backgroundColor: "white",
        contentWidth: "50%", // or percent "50%"
        fontFamily: {
            label: "Helvetica",
            value: "'Helvetica Neue', Helvetica, Arial, sans-serif"
        },
        preheaderText: "Hello World"
    });

    editorRef.value = editor

});


defineExpose({
    editor : editor, 
    setToNewTemplate : setToNewTemplate,
    ready : loadingReady
})


</script>

<template>
    <div v-if="isLoading">
        <span>Loading...</span>
    </div>
    <div class="unlayer" v-else :id="editorId"></div>
</template>

<style>
.unlayer {
    height: calc(100vh - 177px);
}
</style>