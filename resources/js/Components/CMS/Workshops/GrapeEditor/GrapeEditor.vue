
  
<script setup lang="ts">
import { onMounted } from "vue";
import 'grapesjs/dist/css/grapes.min.css';
import grapesjs from 'grapesjs';
import Basic from 'grapesjs-blocks-basic';
import GrapesForm from 'grapesjs-plugin-forms';
import TailwindComponents from 'grapesjs-tailwind';
import Webpage from 'grapesjs-preset-webpage';
import { ref } from 'vue'
import { panel } from './Panel'
import {  get } from "firebase/database"
import {
    getDbRef,
    setDataFirebase,
} from "@/Composables/firebase";


const data = ref(null)

const save = async(newData) => {
  const column = "org/websites/webpages"
    try {
        await setDataFirebase(column,newData);
    } catch (error) {
        console.error(error);
    }
}

const escapeName = (name) =>
  `${name}`.trim().replace(/([^a-z0-9\w-:/]+)/gi, "-");

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
      TailwindComponents,
      Webpage
    ],
    storageManager: {
      type: 'remote',
      options: {
        remote: {
          onStore: (data, editor) => {
            const pagesHtml = editor.Pages.getAll().map(page => {
              const component = page.getMainComponent();
              return {
                html: editor.getHtml({ component }),
                css: editor.getCss({ component })
              }
            });
            const savedData = { id: 'projectID', data, pagesHtml };
            save(savedData);

            return savedData;
          },
          onLoad: async(result) => {
            const snapshot = await get(getDbRef("org/websites/webpages"))
            const firebaseData = snapshot.exists() ? snapshot.val() : null
            result.data = firebaseData.data
            console.log('inii',firebaseData, result)
            return firebaseData.data
          },
        }
      },
    },
  });
});
</script> 

<template>
  <div id="gjs"></div>
</template>
  
<style>
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
  outline: none;
  box-shadow: 0 0 0 2pt #c5c5c575;
}

#gjs {
  border: none;
}

.gjs-one-bg {
  background-color: #f9fafb;
}

.gjs-two-color {
  color: black;
}

.gjs-three-bg {
  background-color: #f9fafb;
  color: c7c8c9;
}

.gjs-four-color,
.gjs-four-color-h:hover {
  color: black;
}
</style>
  