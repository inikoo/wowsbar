<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { loadCss } from "@/Composables/loadCss";
const header = usePage().props.structure.header;
const footer = usePage().props.structure.footer;
const user = usePage().props.auth.user;
let dynamicClasses = {
    header: "",
    footer: "",
};
onMounted(() => {
    const css = {
        header: header[0] ? loadCss(header[0].css) : [],
        footer: footer[0] ? loadCss(footer[0].css) : [],
    };
    for (const selector in css) {
        for (const property in css[selector]) {
            let classString = "";
            for (const c in css[selector][property]) {
                classString += `${c}: ${css[selector][property][c]};`;
                dynamicClasses[selector] += `${property} { ${classString} } `;
            }
        }
    }
    const styleElement = document.createElement("style");
    styleElement.textContent = dynamicClasses.header + dynamicClasses.footer;
    document.head.appendChild(styleElement);
    // set Login Button logic
    const loginButton = document.querySelectorAll(
        '*[data-wowsbar-element="login"]'
    );
    // console.log(user)
    if (user) {
        loginButton.forEach((button) => {
            button.innerHTML = 'Dashboard'
        });
    }
});
</script>

<template>
    <div v-html="header[0]?.html" ></div>
    <slot />
    <div v-html="footer[0]?.html"></div>
    <notifications dangerously-set-inner-html :max="3" :width="500" />
</template>


<style>
@import url("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
a {
    color: black;
    text-decoration: none !important;
}
</style>