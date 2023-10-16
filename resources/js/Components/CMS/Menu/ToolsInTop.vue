<script setup lang="ts">
import { faHandPointer, faHandRock, faPlus } from '@fas/';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { ref } from 'vue'

library.add(faHandPointer, faHandRock, faPlus)
const props = defineProps({
  tool: Object,
  columnSelected: Number,
  navigation: Object
});


const emits = defineEmits();
const theme = ref(props.navigation.type)
const themeOption = ['simple', 'simple 2']

const Bluprint = () => {
  const set = [
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
        column: props.navigation.items.map((item, index) => index + 1)
      },
    }
  ]
  return set
}

const selectTool = (t) => {
  for (const item in props.tool) {
    props.tool[item] = t[item]
  }
}

const columChange = (index) => {
  emits('setColumnSelected', index)
}


</script>

<template>
  <div class="h-[40px] bg-white p-[5px] w-full flex">
    <div class="w-1/2  justify-start items-center hidden sm:flex">
      <div v-for="item in Bluprint().filter((item) => item.position === 'right')" :key="item.name">
        <div v-if="item.name === 'handTool'" v-for="t of item.optionsData.tools" :key="t.name"
          class="inline-block bg-gray-300 py-1 px-2 rounded-md text-[11px] mx-1"
          :class="{'outline outline-1': t.name === tool.name }" @click="selectTool(t)">
          <font-awesome-icon :icon="t.icon" />
        </div>

        <div v-if="item.name === 'activeColumn'"
          v-for="(columnItem, columnIndex) in item.optionsData.column" :key="columnIndex"
          @click="columChange(columnIndex)" class="inline-block bg-gray-300 py-1 px-2 rounded-md text-xs mx-1"
          :class="{ 'outline outline-2': columnIndex == columnSelected }">
          {{ columnItem }}
        </div>
        <div v-if="item.name === 'activeColumn'" @click="emits('addNavigation')"
          class="inline-block bg-gray-300 py-1 px-2 rounded-md text-xs font-bold mx-1">
          +
        </div>
      </div>
    </div>

    <div class="w-full flex justify-end items-cente sm:w-1/2 ">
      <div v-for="item in Bluprint().filter((item) => item.position === 'left')" :key="item.name">
        <select v-model="theme" @change="emits('changeTheme',theme)" v-if="item.name === 'theme'"
          class="px-2 py-1 rounded-md border-gray-300 border w-[200px]">
          <option v-for="option in item.optionsData.options" :key="option" :value="option">{{ option }}
          </option>
        </select>
      </div>
    </div>
  </div>
</template>


<style></style>



