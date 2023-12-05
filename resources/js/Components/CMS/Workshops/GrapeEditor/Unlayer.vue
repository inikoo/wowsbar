<script setup lang="ts">
import { onMounted } from "vue";
import { loadScript, getNextEditorId, useUnlayer } from "./script-loader";
import axios from "axios"

const props = withDefaults(defineProps<{
    updateRoute?: Object;
    loadRoute?: Object;
    imagesUploadRoute?: Object
}>(), {});


const { isLoaded, isLoading } = useUnlayer();
// get last editor id
const editorId = getNextEditorId();
// variable untuk menyimpan instance editor
let editor = null;


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
        /*  emits('onSaveToServer', response?.data?.isDirty) */


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
            console.log(response)
            return response.data.html.design
        }
    } catch (error) {
        console.log(error)
    }
}


onMounted(async () => {
    //loadeditor
    await loadScript();

    const opt = {
        id: editorId,
        displayMode: 'email',
        user: {
            id: 1,
            signature: 'XXX',
            name: 'John Doe', // optional
            email: 'aryarajasa0@gmail.com'
        },
        features: {
            sendTestEmail: true
        },
        mergeTags: [
            { name: "Email", value: "{{email}}" },
            { name: "First Name", value: "{{first_name}}" },
            { name: "Last Name", value: "{{last_name}}" },
            { name: "Unsubscribe", value: "{{unsubscribe}}" }
        ],
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
            console.log(response.data)
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

});


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