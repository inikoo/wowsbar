<script setup lang="ts">
import { loadCss } from "@/Composables/loadCss";
import { ref, onMounted } from "vue";
import Html from "@/Components/Blocks/Html.vue";
import Appointment from "@/Components/Blocks/Appointment.vue";



const props = defineProps<{
    structure: object
    content: {
        css: String;
        js: String;
        blocks: Array;
    }
    auth: object
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

    // set Login Button logic
    const loginButton = document.querySelectorAll(
        '*[data-wowsbar-element="login"]'
    );
    if (props.auth.user) {
        loginButton.forEach((button) => {
            button.innerHTML = 'Dashboard'
        });
    }
});

</script>

<template layout="Public">
    <!-- <div v-html="props.structure.header[0]?.html"></div> -->
    <div v-for="(blockData, index) in content.blocks" :key="index">
        <component :is="getComponent(blockData['type'])" :data="blockData.content">
        </component>
    </div>
    <!-- <div v-html="props.structure.footer[0]?.html"></div> -->
</template>

<style scoped>
@import url("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
</style>