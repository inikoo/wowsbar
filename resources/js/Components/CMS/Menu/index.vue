<script setup lang="ts">
import Menu1 from './NavbarOneWorkshop/MenuOneWorkshop.vue'
import Menu2 from './NavbarTwoWorkshop/MenuTwoWorkshop.vue'


const props = defineProps<{
    theme: String,
    navigation : Object
    tool:Object
    selectedNav?:Object
    changeNavActive : Function
    layout : Object
  
}>()


const component = {
    "simple": Menu1,
    "simple 2": Menu2,
}



</script>

<template>
    <div>
        <component :is="component[theme]" :navigation="navigation"  :tool="tool" :selectedNav="selectedNav" :changeNavActive ="changeNavActive" :layout="layout"/>
    </div>
</template>

<style scoped></style>