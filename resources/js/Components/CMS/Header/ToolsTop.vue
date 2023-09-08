<script setup lang="ts">
import { get } from "lodash"
import ColorPicker from "@/Components/Workshop/Fields/ColorPicker.vue";
import FontSize from '@/Components/CMS/Fields/Fontsize.vue'
import FontDecorator from '@/Components/CMS/Fields/FontDecorator.vue'
const props = defineProps<{
    data: Array
    layerActive: Object
}>()
</script>

<template>
    <div class="h-[6%] bg-white p-[5px] w-full flex gap-3" v-if="data[layerActive].type == 'text'">
        <div>
            <span aria-hidden="true">
                <ColorPicker :data="data[layerActive]" :fieldName="['style','color']" :colorSuggestions="false" stylePanel="top:330px" @click="(e)=>e.stopPropagation"/>
            </span>
        </div>
        <div>
            <span aria-hidden="true">
                <FontSize :data="data[layerActive]" :fieldName="['style','fontSize']"  @click="(e) => e.stopPropagation()" />
            </span>
        </div>
        <div>
            <span aria-hidden="true">
              <FontDecorator :data="data[layerActive]" :fieldName="['style']"
                    @click="(e) => e.stopPropagation()" />
            </span>
        </div>

    </div>
</template>

<style>
</style>



