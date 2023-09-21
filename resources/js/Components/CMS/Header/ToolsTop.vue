<script setup lang="ts">
import { faHandPointer, faHandRock, faPlus } from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';

library.add(faHandPointer, faHandRock, faPlus)
const props = defineProps({
  tool: Object,
  theme: Object,
});


const emits = defineEmits();

const themeOption = [
        { name: 'Simple', value: 1 },
        { name: 'simple Sticky', value: 2 },
]

const Bluprint = [
  {
    name: 'theme',
    position: 'left',
    optionsData: {
      options: themeOption
    }
  },
]


const setTheme=(value)=>{
  const data = themeOption.find((item)=> item.value == value)
  for(const t in props.theme){
    props.theme[t] = data[t]
  }
  console.log(props)
}


</script>

<template>
  <div class="h-[40px] bg-white p-[5px] w-full flex">
    <div class="w-1/2 flex justify-start items-center">
    </div>

    <div class="w-1/2 flex justify-end items-center">
      <div v-for="item in Bluprint.filter((item) => item.position === 'left')" :key="item.name">
        <select v-model="theme.value" @change="setTheme(theme.value)" v-if="item.name === 'theme'"
          class="px-2 py-1 rounded-md border-gray-300 border w-[200px]">
          <option v-for="option in item.optionsData.options" :key="option.value" :value="option.value">{{ option.name }}
          </option>
        </select>
      </div>
    </div>
  </div>
</template>
  

<style></style>



