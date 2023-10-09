<script setup lang="ts">
import { onMounted } from 'vue';
import { loadCss } from '@/Composables/loadCss';

const props = defineProps<{
  content: object
}>()


const css = props.content[0] ? loadCss(props.content[0].css) : []
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

<template layout="Public">
  <div v-if="content[0]">
    <div v-html="content[0].html" :class="dynamicClasses"></div>
  </div>
  <div v-else>
    you don't have any content to display
  </div>

</template>

