<script setup lang="ts">
import { loadCss } from '@/Composables/loadCss';
import { ref, onMounted } from "vue";
import Html from '@/Components/Blocks/Html.vue'

const props = defineProps<{
    content: {
        css: String,
        js: String,
        blocks: Array
    }
}>()

const getComponent = (componentName: string) => {
    const components: any = {
        'html': Html,
    };
    return components[componentName] ?? null;

};

console.log('props', props.content)
const css = loadCss(props.content.css)
let dynamicClasses = '';
onMounted(() => {
    // Generate dynamic CSS classes based on the parsed styles
    for (const selector in css) {
        let classString = '';
        for (const property in css[selector]) {
            classString += `${property}: ${css[selector][property]};`;
        }
        dynamicClasses += `${selector} { ${classString} } `;
    }

    // Append the dynamic styles to the <style> block using a new style element
    const styleElement = document.createElement('style');
    styleElement.textContent = dynamicClasses;
    document.head.appendChild(styleElement);
});

</script>

<template>
       <div v-for="(blockData,index) in content.blocks" :key="index" >
        <component :is="getComponent(blockData['type'])" :data="blockData.content" >
    </component>
       </div>
   
</template>
