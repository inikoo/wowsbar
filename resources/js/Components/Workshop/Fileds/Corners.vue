<script setup lang="ts">
import { trans } from "laravel-vue-i18n"
import { ref, watch, computed, defineExpose  } from 'vue'
import Input from '@/Components/Forms/Fields/Input.vue'
import { get, cloneDeep }  from 'lodash'

const props = defineProps<{
  form: any,
  fieldName: string,
  options?: any,
  fieldData?: {
    placeholder: string
    readonly: boolean
    copyButton: boolean
  }
}>()


const area = ref(null)


const corners = [
  {label : trans('top left'), valueForm : get(props.form.layout,['corners',`topLeft`],null), id : 'topLeft' },
  {label : trans('Top right'), valueForm : get(props.form.layout,['corners',`topRight`],null), id : 'topRight' },
  {label : trans('bottom left'), valueForm : get(props.form.layout,['corners',`bottomLeft`],null), id : 'bottomLeft' },
  {label : trans('Bottom right'), valueForm : get(props.form.layout,['corners',`bottomRight`],null), id : 'bottomRight' },
]

const Type = [
  {
    label: 'Footer',
    value: 'cornerFooter',
    fields: [
      {
        name:  'text',
        type: 'input',
        label: trans('Text'),
        value: null
      },
    ]
  },
  {
    label: 'Corner text',
    value: 'cornerText',
    fields: [
      {
        name: 'title',
        type: 'input',
        label: trans('title'),
        value: null
      },
      {
        name:  'subtitle',
        type: 'input',
        label: trans('subtitle'),
        value: null
      },
    ]
  },
  {
    label: 'Link button',
    value: 'linkButton',
    fields: [
      {
        name: 'text',
        type: 'input',
        label: trans('text'),
        value: null
      },
      {
        name:  'target',
        type: 'input',
        label: trans('subtitle'),
        value: null
      },
    ]
  },
]

const defaultCurrent = computed(() => {
  if (area.value != null) {
    const areaType = props.form.layout.corners[area.value.id]?.type;
    const index =  Type.findIndex(item => item.value === areaType);
    return index == -1 ? 0 : index
  } else {
    return 0; // Return 0 if area.value is null
  }
});

  // Set current to the default index
  const current = ref(defaultCurrent.value)

const currentTypeFields = computed(() => {
  const currentType = Type[current.value]
  return currentType.fields.map((field) => {
    return {
      ...field,
      value: currentType.value === Type[current.value].value && get(area.value,['valueForm','type']) == currentType.value ? get(area.value,['valueForm','data',`${field.name}`]) : null,
    }
  })
})


const handleClick = (corner) => {
  area.value = corner;
  current.value = defaultCurrent.value
  setUpData();
}

const setUpData= ()=>{
  const currentType = Type[current.value]
  let data = {}
  for(const s of currentTypeFields.value){
    data[s.name] = s.value
  }
  let setData = cloneDeep(props.form.layout.corners[area.value.id])
  setData = {
    type : currentType.value,
    data : {...data}
  }
  props.form.layout.corners[area.value.id] = setData
  console.log(props.form.layout.corners)
}

watch(current, () => {
    setUpData();
  });


defineExpose({
  setUpData,
});

</script>


<template>
  <div class="h-64">
    <div class="grid grid-cols-2 gap-2 h-full">
      <div
        v-for="(corner, index) in corners"
        :key="corner.id"
        :class="['border', 'flex-grow',{ 'bg-red-200': get(area,'id') == corner.id }]"
        @click="handleClick(corner)"
      >
        {{ corner.label }}
      </div>
    </div>
  

    <div v-if="area != null">
      <div class="w-full flex mt-3">
      <span class="isolate flex w-full rounded-md shadow-sm">
        <!-- Use v-for to loop through buttonType array -->
        <button v-for="(item, key) in Type" :key="item.value" type="button" @click="current = key"
        :class="['flex-grow', 'relative', 'inline-flex', 'items-center', 'justify-center', 'px-3', 'py-2', 'text-sm', 'font-semibold', 'text-gray-900', 'ring-1', 'ring-inset', 'ring-gray-300', 'focus:z-10', { 'bg-orange-500': current === key }]">
          {{ item.label }}
        </button>
      </span>
    </div>



    <div v-for="(fieldData, index ) in currentTypeFields" :key="index" class="mt-2.5">
      <dl class="divide-y divide-green-200  ">
        <div class="pb-4 sm:pb-5 sm:grid sm:grid-cols-3 sm:gap-4 max-w-2xl">
          <dt class="text-sm font-medium text-gray-500 capitalize">
            <div class="inline-flex items-start leading-none">

              <FontAwesomeIcon v-if="fieldData.required" :icon="['fas', 'asterisk']"
                class="font-light text-[12px] text-red-400 mr-1" />
              <span>{{ fieldData.label }}</span>
            </div>
          </dt>
          <dd class="sm:col-span-2">
            <div class="mt-1 flex text-sm text-gray-700 sm:mt-0">
              <div class="relative flex-grow">
                <input
                  v-model = fieldData.value
                  @change="setUpData"
                  class="block w-full shadow-sm rounded-md dark:bg-gray-600 dark:text-gray-400 focus:ring-gray-500 focus:border-gray-500 sm:text-sm border-gray-300 dark:border-gray-500 read-only:bg-gray-100 read-only:ring-0 read-only:ring-transparent read-only:text-gray-500"
              />
         
              </div>
            </div>
          </dd>
        </div>
      </dl>
    </div>

    </div>
    

  </div>
</template>
