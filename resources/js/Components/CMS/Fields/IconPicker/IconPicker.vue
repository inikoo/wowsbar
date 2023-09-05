<script setup>
import { ref, computed } from 'vue'
import fontLibrary from './Components/fonts.js'
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
import { faEnvelope as fasEnvelope, faPhone as fasPhone, faBuilding as fasBuilding, faCircle as fasCircle, faMap as fasMap, faUser as fasUser } from '../../../../../private/pro-solid-svg-icons';
import { faEnvelope as falEnvelope, faPhone as falPhone, faBuilding as falBuilding, faCircle as falCircle, faMap as falMap, faUser as falUser } from '../../../../../private/pro-light-svg-icons';
import { faEnvelope as farEnvelope, faPhone as farPhone, faBuilding as farBuilding, faCircle as farCircle, faMap as farMap, faUser as farUser } from '../../../../../private/pro-regular-svg-icons';
import { faEnvelope as fadEnvelope, faPhone as fadPhone, faBuilding as fadBuilding, faCircle as fadCircle, faMap as fadMap, faUser as fadUser } from '../../../../../private/pro-duotone-svg-icons';
import { faTiktok, faFacebook, faFacebookF, faSquareFacebook, faInstagram, faSquareInstagram , faWhatsapp, faSquareWhatsapp } from "@fortawesome/free-brands-svg-icons"
import { library } from '@fortawesome/fontawesome-svg-core'
import Popover from '@/Components/Utils/Popover.vue'
library.add( 
faTiktok, faFacebook, faFacebookF, faSquareFacebook, faInstagram, faSquareInstagram , faWhatsapp, faSquareWhatsapp, 
fasEnvelope, fasPhone, fasBuilding, fasCircle, fasMap, fasUser,
falEnvelope, falPhone, falBuilding, falCircle, falMap, falUser,
farEnvelope, farPhone, farBuilding, farCircle, farMap, farUser,
fadEnvelope, fadPhone, fadBuilding, fadCircle, fadMap, fadUser,
)

console.log('lololo', library)

const props = defineProps({
  label: {
    type: String,
    default: 'Icon Picker'
  },
  modelValue: {
    type: String,
    default: 'fas fa-circle'
  },
  cssClass : String,
  save:Function,
  data:Object
})

const filterText = ref('')
const activeGlyph = ref(props.modelValue)
const isVisible = ref(false)

const tabs = [
      {
        id: 'all',
        title: 'All Icons',
        link: 'all'
      },
    ]

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
      insert()
    }

    const isActiveGlyph = glyph => {
      return activeGlyph.value == glyph
    }

    const getGlyphName = glyph =>
      glyph.replace(/f.. fa-/g, '').replace('-', ' ')

    const insert = () => {
      props.save({column : {...props.data}, value : activeGlyph.value})
    }   

</script>


<template>
  <Popover>
  <template #button><FontAwesomeIcon :icon="props.modelValue" :class="cssClass" aria-hidden="true" /></template>
  <template #content>
  <div class="flex gap-4">
    <div
      v-for="glyph in glyphs"
      :key="glyph"
      :class="{ 'aesthetic-selected': isActiveGlyph(glyph) }"
      @click="setActiveGlyph(glyph)"
    >
      <div class="aim-icon-item-inner">
        <FontAwesomeIcon :icon="glyph"/>
      </div>
    </div>
  </div>
    </template>
  </Popover>
  <!-- <div class="aim-modal aim-open" v-if="isVisible">
    <div class="aim-modal--content">
      <div class="aim-modal--header">
        <div class="aim-modal--header-logo-area">
          <span class="aim-modal--header-logo-title">
            {{ label }}
          </span>
        </div>
        <div class="aim-modal--header-close-btn" @click="closePicker">
          <FontAwesomeIcon icon="fas fa-times"/>
        </div>
      </div>
      <div class="aim-modal--body">
        <div class="aim-modal--icon-preview-wrap">
          <div class="aim-modal--icon-search">
            <input v-model="filterText" placeholder="Filter by name..." />
          </div>
          <div class="aim-modal--icon-preview-inner">
            <div class="aim-modal--icon-preview">
              <div
                class="aim-icon-item"
                v-for="glyph in glyphs"
                :key="glyph"
                :class="{ 'aesthetic-selected': isActiveGlyph(glyph) }"
                @click="setActiveGlyph(glyph)"
              >
                <div class="aim-icon-item-inner">
                  <FontAwesomeIcon :icon="glyph"/>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="aim-modal--footer">
        <button class="aim-insert-icon-button" @click="insert">Insert</button>
      </div>
    </div>
  </div> -->
</template>




