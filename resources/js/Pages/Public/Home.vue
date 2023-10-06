<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Mon, 31 Jul 2023 16:01:32 Malaysia Time, Kuala Lumpur, Malaysia
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->
<script setup lang="ts">

const props = defineProps<{
    content: object
}>()

console.log(props.content)
</script>

<template layout="Public">
     <div v-html="content.pagesHtml[0].html"></div>
     <div v-html="content.pagesHtml[0].html"></div>
     <div v-html="content.pagesHtml[0].html"></div>
</template>
