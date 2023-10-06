<script setup lang="ts">
import { defineProps, ref, onMounted } from 'vue';

const props = defineProps<{
  content: object
}>()

const parseCSSString = (cssString) => {
  const styles = {};
  const rules = cssString.split('}');
  rules.forEach(rule => {
    const [selectors, declaration] = rule.split('{');
    if (selectors && declaration) {
      const selectorList = selectors.trim().split(',');
      const properties = declaration.split(';').filter(prop => prop.trim() !== '');
      const ruleStyles = {};
      properties.forEach(property => {
        const [key, value] = property.split(':').map(item => item.trim());
        if (key && value) {
          ruleStyles[key] = value;
        }
      });
      selectorList.forEach(selector => {
        styles[selector.trim()] = ruleStyles;
      });
    }
  });

  return styles;
}

const tryit = parseCSSString(props.content.pagesHtml[0].css);
let dynamicClasses = '';

onMounted(() => {
  // Generate dynamic CSS classes based on the parsed styles
  for (const selector in tryit) {
    let classString = '';
    for (const property in tryit[selector]) {
      classString += `${property}: ${tryit[selector][property]};`;
    }
    dynamicClasses += `${selector} { ${classString} } `;
  }

  // Append the dynamic styles to the <style> block using a new style element
  const styleElement = document.createElement('style');
  styleElement.textContent = dynamicClasses;
  document.head.appendChild(styleElement);
});

console.log(props)

</script>

<template>
  <div v-html="content.pagesHtml[0].html" :class="dynamicClasses"></div>
</template>

