<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

  <script setup>
  import { usePage } from '@inertiajs/vue3'
  import { ref, computed, reactive, onBeforeMount } from 'vue'
  // import Image from "@/Components/Image.vue"
  // import Cookies from '@/Components/Cookies.vue'
  // import FooterTabLanguage from '@/Components/Footer/FooterLanguage.vue'
  // import HeaderThemeOne from '@/Components/Header/Public/HeaderThemeOne.vue'
  import MenuOne from '@/Components/Menu/Public/MenuOne.vue'
  import MenuTwo from '@/Components/Menu/Public/MenuTwo.vue'
  import FooterThemeOne from '@/Components/Footer/Public/FooterThemeOne.vue'
  import FooterThemeTwo from '@/Components/Footer/Public/FooterThemeTwo.vue'
  import FooterThemeThree from '@/Components/Footer/Public/FooterThemeThree.vue'
  import { getDbRef } from '@/Composables/firebase'
  import { set, onValue, get } from "firebase/database"
  import { notify } from "@kyvg/vue3-notification"
  import { faSpinnerThird } from '@/../private/pro-duotone-svg-icons'
  import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome"
  import { library } from "@fortawesome/fontawesome-svg-core"
  import PageHeading from "@/Components/Headings/PageHeading.vue";
  import { Head } from '@inertiajs/vue3'
  import { capitalize } from "@/Composables/capitalize"
  import HeaderThemeOne from '@/Components/Header/Public/HeaderThemeOne.vue'
  library.add(faSpinnerThird );
  const isTabActive = ref(false)

const props = defineProps({
    title: String,
    pageHead: Object,
});
  

const dbPath = 'org/websites'
const data =  ref(null)
const loadingState = ref(true)
const fetchInitialData = async () => {
    try {
        const snapshot = await get(getDbRef(dbPath));
        const firebaseData = snapshot.exists() ? snapshot.val() : null;
        data.value = firebaseData
    } catch (error) {
        console.error(error)
        notify({
            title: "Failed to get realtime data",
            text: 'please reload and make sure your internet connection is stable',
            type: "error"
        });
    }finally {
        loadingState.value = false;
    }
}

onValue(getDbRef(dbPath), (snapshot) => {
    if (snapshot.exists()) {
        const firebaseData = snapshot.val()
           data.value = firebaseData
    }
})


onBeforeMount(fetchInitialData)

const getHeaderComponent = computed(() => {
    const componentList = {
        'HeaderThemeOne': HeaderThemeOne
    }

    console.log(data.value.header)    
    return componentList['HeaderThemeOne']
})
  
  const getMenuComponent = computed(() => {
      const componentList = {
          'MenuOne': MenuOne,
          'MenuTwo': MenuTwo
      }
  
      return componentList[data.value?.menu?.theme?.name ?? 'MenuTwo']
  })
  
  const getFooterComponent = computed(() => {
      const componentList = {
          'FooterThemeOne': FooterThemeOne,
          'FooterThemeTwo': FooterThemeTwo,
          'FooterThemeThree': FooterThemeThree,
      }
  
      return componentList[data.value?.footer?.theme?.name ?? 'FooterThemeOne']
  })


  
  </script>
  
  <template>
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
      <div v-if="loadingState" class="w-full min-h-screen flex justify-center items-center">
            <FontAwesomeIcon icon='fad fa-spinner-third' class='animate-spin h-12  text-gray-600' aria-hidden='true' />
        </div>
      <div v-else class="relative" :class="[data.layout == 'full' ? '' : 'flex justify-center w-full']" >
          <section class="relative isolate overflow-hidden bg-gray-100">
              <!-- <component :is="getHeaderComponent" :data="data.header"></component> -->

              <component :is="getMenuComponent" :data="data.menu"></component>
  
              <!-- Background: Square line -->
              <svg class="absolute inset-0 -z-10 h-full w-full stroke-gray-200 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
                  aria-hidden="true">
                  <defs>
                      <pattern id="0787a7c5-978c-4f66-83c7-11c213f99cb7" width="200" height="200" x="50%" y="-1"
                          patternUnits="userSpaceOnUse">
                          <path d="M.5 200V.5H200" fill="none" />
                      </pattern>
                  </defs>
                  <rect width="100%" height="100%" stroke-width="0" fill="url(#0787a7c5-978c-4f66-83c7-11c213f99cb7)" />
              </svg>
  
              <!-- Main content of page -->
              <slot />
  
              <component :is="getFooterComponent" :data="data.footer.data" />
          </section>
      </div>
  
      <!-- <Cookies /> -->
  </template>
  
  