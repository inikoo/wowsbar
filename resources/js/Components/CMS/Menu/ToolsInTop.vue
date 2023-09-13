<script setup lang="ts">
import { faHandPointer, faHandRock, faPlus } from '@/../private/pro-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';

library.add(faHandPointer, faHandRock, faPlus)
const props = defineProps({
  tool: Object,
  theme: Object,
  columSelected: Number,
  navigation:Object
});


const emits = defineEmits();


const themeOption =[
        { name: 'MenuOne', value: '1' },
        { name: 'MenuTwo', value: '2' },
      ]
const Bluprint = [
  {
    name: 'handTool', position: 'right',
    optionsData: {
      tools: [
        { name: 'edit', icon: ['fas', 'fa-hand-pointer'] },
        { name: 'grab', icon: ['fas', 'hand-rock'] },
      ]
    }
  },
  {
    name: 'theme',
    position: 'left',
    optionsData: {
      options: themeOption
    }
  },
  {
    name: 'activeColumn', position: 'right',
    optionsData: {
      column: props.navigation.categories.map((item, index) => index + 1)
    },
  }
]

const selectTool = (t) => {
  for (const item in props.tool) {
    props.tool[item] = t[item]
  }
}

const columChange = (index) => {
  emits('setColumnSelected', index)
}


const setTheme=(value)=>{
  const data = themeOption.find((item)=> item.value == value)
  console.log(data,value)
  for(const t in props.theme){
    props.theme[t] = data[t]
  }
}

</script>

<template>
  <div class="h-[40px] bg-white p-[5px] w-full flex">
    <div class="w-1/2 flex justify-start items-center">
      <div v-for="item in Bluprint.filter((item) => item.position === 'right')" :key="item.name">
        <div v-if="item.name === 'handTool'" v-for="t of item.optionsData.tools" :key="t.name"
          class="inline-block bg-gray-300 py-1 px-2 rounded-md text-[11px] mx-1"
          :class="{ 'outline outline-1': t.name === tool.name }" @click="selectTool(t)">
          <font-awesome-icon :icon="t.icon" />
        </div>

        <div v-if="item.name === 'activeColumn' && theme.value != 3" v-for="(columnItem, columnIndex) in item.optionsData.column"
          :key="columnIndex" @click="columChange(columnIndex)"
          class="inline-block bg-gray-300 py-1 px-2 rounded-md text-xs mx-1"
          :class="{ 'outline outline-2': columnIndex  == columSelected }">
          {{ columnItem }}
        </div>
      </div>
    </div>

    <div class="w-1/2 flex justify-end items-center">
      <div v-for="item in Bluprint.filter((item) => item.position === 'left')" :key="item.name">
        <select v-model="theme.value" @change="setTheme(theme.value)" v-if="item.name === 'theme'"
          class="px-2 py-1 rounded-md border-gray-300 border w-[150px]">
          <option v-for="option in item.optionsData.options" :key="option.value" :value="option.value">{{ option.name }}
          </option>
        </select>
      </div>
    </div>
  </div>
</template>
  

<style></style>



