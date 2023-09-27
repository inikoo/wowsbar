
  
<script setup lang="ts">
import { onMounted } from "vue";
import 'grapesjs/dist/css/grapes.min.css';
import grapesjs from 'grapesjs';
import basic from 'grapesjs-blocks-basic';
import plugin from 'grapesjs-preset-webpage'
import TailwindComponents from 'grapesjs-tailwind';
import { ref } from  'vue'



const data = ref(null)

const save = (newData) => {
  console.log(newData)
  data.value = newData
}

onMounted(() => {
  const editorInstance = grapesjs.init({
    fromElement: true,
    height: "100%",
    container: "#gjs",
    forceClass: false,
    plugins: [basic, TailwindComponents],
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
            
    //         // Call the save function here with the saved data
    //         save(savedData);

    //         return savedData;
    //       },
    //       onLoad: result => result.data,
    //     }
    //   },
    // },
  });
  editorInstance.runCommand('get-tailwindCss');
});



</script> 

<template>
  <div id="gjs"></div>
</template>
  
<style>
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
  padding: 0px;
}
</style>
  