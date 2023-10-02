

<script setup lang="ts">
import CardContentOne from './CardContentOne.vue';
import CardContentTwo from './CardContentTwo.vue';
import CardContentThree from './CardContentThree.vue';
import { computed, ref } from 'vue';
import ButtonTheme from '@/Components/CMS/Utils/ButtonTheme.vue'


const CardTheme = ref(0)

const people = [
  {
    name:"<p style=\"text-align: center\"><strong><span style=\"font-size: 20px; color: #fffafa\">Leonard Krasner</span></strong></p>",
    role: "<p style=\"text-align: center\"><span style=\"color: #ffffff\">Senior Designer</span></p>",
    imageUrl:
      'https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80',
    bio: 'Quia illum aut in beatae. Possimus dolores aliquid accusantium aut in ut non assumenda. Enim iusto molestias aut deleniti eos aliquid magnam molestiae. At et non possimus ab. Magni labore molestiae nulla qui.',
  },
  {
    name:"<p style=\"text-align: center\"><strong><span style=\"font-size: 20px; color: #fffafa\">Leonard Krasner</span></strong></p>",
    role: "<p style=\"text-align: center\"><span style=\"color: #ffffff\">Senior Designer</span></p>",
    imageUrl:
      'https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80',
    bio: 'Quia illum aut in beatae. Possimus dolores aliquid accusantium aut in ut non assumenda. Enim iusto molestias aut deleniti eos aliquid magnam molestiae. At et non possimus ab. Magni labore molestiae nulla qui.',
  },
  {
    name: "<p style=\"text-align: center\"><strong><span style=\"font-size: 20px; color: #fffafa\">Leonard Krasner</span></strong></p>",
    role:"<p style=\"text-align: center\"><span style=\"color: #ffffff\">Senior Designer</span></p>",
    imageUrl:
      'https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80',
    bio: 'Quia illum aut in beatae. Possimus dolores aliquid accusantium aut in ut non assumenda. Enim iusto molestias aut deleniti eos aliquid magnam molestiae. At et non possimus ab. Magni labore molestiae nulla qui.',
  },
  {
    name:"<p style=\"text-align: center\"><strong><span style=\"font-size: 20px; color: #fffafa\">Leonard Krasner</span></strong></p>",
    role: "<p style=\"text-align: center\"><span style=\"color: #ffffff\">Senior Designer</span></p>",
    imageUrl:
      'https://images.unsplash.com/photo-1519345182560-3f2917c472ef?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=8&w=1024&h=1024&q=80',
    bio: 'Quia illum aut in beatae. Possimus dolores aliquid accusantium aut in ut non assumenda. Enim iusto molestias aut deleniti eos aliquid magnam molestiae. At et non possimus ab. Magni labore molestiae nulla qui.',
  },
]

const pageHead = {
  title : "<p style=\"text-align: center\"><strong><span style=\"font-size: 36px\">Meet our team</span></strong></p>",
  description :  "<p style=\"text-align: center\"><span style=\"color: #fffafa\">We’re a dynamic group of individuals who are passionate about what we do.</span></p>"
}

const components = {
    0: CardContentOne,
    1: CardContentTwo,
    2: CardContentThree
}


const component = computed(()=>{
    return components[CardTheme.value]
})

</script>

<template>
  <div>
    <ButtonTheme v-model="CardTheme" :label="['Simple', 'wide', 'image']"/>
      <component :is="component" :data="people" :pageHead="pageHead"/>
  </div>
</template>

<style scoped></style>