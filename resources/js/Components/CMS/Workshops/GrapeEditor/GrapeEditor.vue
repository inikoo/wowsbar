
  
<script setup lang="ts">
import { onMounted } from "vue";
import 'grapesjs/dist/css/grapes.min.css';
import grapesjs from 'grapesjs';
import basic from 'grapesjs-blocks-basic';
import TailwindComponents from 'grapesjs-tailwind';
import { ref } from  'vue'

const save=(data)=>{
 console.log(data)
}

const data = ref(null)

onMounted(() => {
  console.log(grapesjs.version);
  const editorInstance = grapesjs.init({
    fromElement: true,
    height: "100vh",
    container: "#gjs",
    forceClass: false,
    plugins: [basic, TailwindComponents],
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
            
            // Call the save function here with the saved data
            save(savedData);

            return savedData;
          },
          onLoad: result => result.data,
        }
      },
    },
  });
});



</script> 

<template>
  <div id="gjs"></div>
  <div>
  </div>
</template>
  
<style>
#gjs {
  border: 3px solid #444;
}

.gjs-pn-panel {
  padding: 0px;
}
</style>
  