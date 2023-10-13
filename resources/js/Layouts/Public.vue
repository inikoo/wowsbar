<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

<script setup>
import { usePage } from "@inertiajs/vue3";
import { onMounted, ref } from "vue";
import { loadCss } from "@/Composables/loadCss";
import processClasses from "https://cdn.statically.io/gh/mudgen/runcss/master/src/runcss.min.js";


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

    for (const element of document.querySelectorAll("*[class]")) {
        const styles = processClasses(element.classList);
    }
    document.body.style.display = "block";



    // set Login Button logic
    const loginButton = document.querySelectorAll(
        '*[data-wowsbar-element="login"]'
    );
    console.log(user)
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
</template>

<style>
@import url("https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css");
</style>