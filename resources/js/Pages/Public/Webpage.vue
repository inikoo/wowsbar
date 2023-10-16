<script setup lang="ts">
import { loadCss } from "@/Composables/loadCss";
import { ref, onMounted } from "vue";
import Html from "@/Components/Blocks/Html.vue";
import Appointment from "@/Components/Blocks/Appointment.vue";
const props = defineProps<{
    content: {
        css: String;
        js: String;
        blocks: Array;
    };
}>();


const getComponent = (componentName: string) => {
    const components: any = {
        html: Html,
        appointment: Appointment,
    };
    return components[componentName] ?? null;
};

const css = props.content.css ? loadCss(props.content.css) : null
let dynamicClasses = "";
onMounted(() => {
    // Append css style
    for (const selector in css) {
        let classString = "";
        for (const property in css[selector]) {
            classString += `${property}: ${css[selector][property]};`;
        }
        dynamicClasses += `${selector} { ${classString} } `;
    }

    // Append the dynamic styles to the <style> block using a new style element
    const styleElement = document.createElement("style");
    styleElement.textContent = dynamicClasses;
    document.head.appendChild(styleElement);

    // Append tailwind
    for (const element of document.querySelectorAll("*[class]")) {
        // processClasses(element.classList);
    }
    document.body.style.display = "block";
});

</script>

<template layout='Public'>
    <div v-for="(blockData, index) in content.blocks" :key="index">
        <component
            :is="getComponent(blockData['type'])"
            :data="blockData.content"
        >
        </component>
    </div>
</template>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
</style>