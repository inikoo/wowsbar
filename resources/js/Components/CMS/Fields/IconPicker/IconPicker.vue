<script setup>
import { ref, computed } from 'vue'
import fontLibrary from './Components/fonts.js'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faEnvelope as fasEnvelope, faPhone as fasPhone, faBuilding as fasBuilding, faCircle as fasCircle, faMap as fasMap, faUser as fasUser, faStar } from '@fas/';
import { faEnvelope as falEnvelope, faPhone as falPhone, faBuilding as falBuilding, faCircle as falCircle, faMap as falMap, faUser as falUser } from '@fal/';
import { faEnvelope as farEnvelope, faPhone as farPhone, faBuilding as farBuilding, faCircle as farCircle, faMap as farMap, faUser as farUser, faDotCircle } from '@far/';
import { faEnvelope as fadEnvelope, faPhone as fadPhone, faBuilding as fadBuilding, faCircle as fadCircle, faMap as fadMap, faUser as fadUser } from '@fad/';
import { faTiktok, faFacebook, faFacebookF, faSquareFacebook, faInstagram, faSquareInstagram , faWhatsapp, faSquareWhatsapp } from "@fortawesome/free-brands-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
import Popover from '@/Components/Utils/Popover.vue'
import { upperFirst } from 'lodash'
library.add(
faTiktok, faFacebook, faFacebookF, faSquareFacebook, faInstagram, faSquareInstagram , faWhatsapp, faSquareWhatsapp,
fasEnvelope, fasPhone, fasBuilding, fasCircle, fasMap, fasUser, faStar,
falEnvelope, falPhone, falBuilding, falCircle, falMap, falUser,
farEnvelope, farPhone, farBuilding, farCircle, farMap, farUser, faDotCircle,
fadEnvelope, fadPhone, fadBuilding, fadCircle, fadMap, fadUser,
)

const props = defineProps({
  cssClass : String,
  data: {
    icon : String
  }
})

const filterText = ref('')
const activeGlyph = ref(props.data.icon)


    const allGlyphs = [].concat(
      fontLibrary.fontAwesome.variants.regular.icons,
      fontLibrary.fontAwesome.variants.solid.icons,
      fontLibrary.fontAwesome.variants.brands.icons
    )

    const glyphs = computed(() => {
      let _glyphs = allGlyphs


      if (filterText.value != '') {
        const _filterText = filterText.value.toLowerCase()
        _glyphs = _glyphs.filter(
          item => item.substr(7, filterText.value.length) === _filterText
        )
      }
      return _glyphs
    })

    const setActiveGlyph = glyph => {
      activeGlyph.value = glyph
      props.data.icon  = activeGlyph.value
    }


    const getGlyphName = glyph =>
      upperFirst(glyph.replace(/f.. fa-/g, '').replace('-', ' '))

</script>


<template>
<Popover>
  <template #button>
    <FontAwesomeIcon :icon="props.data.icon" :class="cssClass" aria-hidden="true" />
  </template>
  <template #content>
    <div class="p-5">
      <div class="w-full flex justify-center p-2.5 text-lg font-medium">Icon Picker</div>
      <div class="flex flex-wrap gap-x-6 gap-y-1 w-80 h-[320px] p-5 overflow-auto">
        <div
        v-for="glyph in glyphs"
        :key="glyph"
        @click="setActiveGlyph(glyph)"
        class="flex flex-col items-center w-1/4 mb-4 text-center cursor-pointer p-2.5 "
        :class="{ 'hover:bg-blue-200 bg-blue-200': activeGlyph === glyph }"
      >
        <div class="mb-2 flex justify-center">
          <!-- Replace with your icon -->
          <FontAwesomeIcon :icon="glyph" class="text-lg text-indigo-500" />
        </div>
        <span class="text-xs">{{ getGlyphName(glyph) }}</span>
      </div>
      </div>

    </div>
  </template>
</Popover>
</template>




