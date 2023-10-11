<script setup lang="ts">
import { onMounted, defineProps } from 'vue';
import { loadCss } from '@/Composables/loadCss';

const props = defineProps<{
  content: object[];
}>()

console.log('porp',props.content)

let dynamicClasses = '';

onMounted(() => {
  const css = props.content[0] ? loadCss(props.content[0].css) : {};

  for (const selector in css) {
    let classString = '';
    for (const property in css[selector]) {
      classString += `${property}: ${css[selector][property]} !important;`;
    }
    dynamicClasses += `${selector} { ${classString} } `;
  }

  // Create a style element for dynamic styles
  const styleElement = document.createElement('style');
  styleElement.textContent = dynamicClasses;

  // Append the style element to the head
  document.head.appendChild(styleElement);
});
</script>

<template>
  <div>
    <!-- <div v-if="content[0]" :class="dynamicClasses" v-html="content[0].html"></div> -->
    <div v-if="content[0]" :class="dynamicClasses" v-html="content[0].html"></div>
    <div v-else>
      Anda tidak memiliki konten untuk ditampilkan.
    </div>
  </div>
</template>