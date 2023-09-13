<!--
  - Author: Raul Perusquia <raul@inikoo.com>
  - Created: Sat, 12 Aug 2023 14:01:37 Malaysia Time, Pantai Lembeng, Bali
  - Copyright (c) 2023, Raul A Perusquia Flores
  -->

  <script setup>
  import { usePage } from '@inertiajs/vue3'
  import { ref, computed, reactive, onBeforeMount } from 'vue'
  
  const publicData = {
      "footer": {
          "data": {
              "column": [
                  {
                      "data": [
                          {
                              "id": "01HA1299DX405K43220P28ZX0C",
                              "link": "#",
                              "name": "Abo"
                          },
                          {
                              "id": "01HA1299DX654X9693N7QX6QVP",
                              "link": "#",
                              "name": "Blog"
                          },
                          {
                              "id": "01HA1299DXT1W1XQM4FH6YFWH1",
                              "link": "#",
                              "name": "Jobs"
                          },
                          {
                              "id": "01HA1299DXA1HB4FAMZCXT6G2D",
                              "link": "#",
                              "name": "Press"
                          },
                          {
                              "id": "01HA1299DXYW5DV49PD49NBDCS",
                              "link": "#",
                              "name": "Partners"
                          }
                      ],
                      "id": "01HA1299DXSPT7BWTWWM30TBG3",
                      "title": "company",
                      "type": "list"
                  },
                  {
                      "data": [
                          {
                              "id": "01HA1299DYKM48G8BABNQ8Y1EJ",
                              "link": "#",
                              "name": "Claim"
                          },
                          {
                              "id": "01HA1299DYYYS4FFYG58FKPA8J",
                              "link": "#",
                              "name": "Privacy"
                          },
                          {
                              "id": "01HA1299DY2QFRSSN7MB02B6PG",
                              "link": "#",
                              "name": "Terms"
                          }
                      ],
                      "id": "01HA1299DYPAT6HHCJ1J47KWG8",
                      "title": "legal",
                      "type": "list"
                  },
                  {
                      "data": "Lorem Ipsum is simply dummy te printernto electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.",
                      "id": "01HA1299DY2BEH1TXR06EYCPX2",
                      "title": "Description",
                      "type": "description"
                  },
                  {
                      "data": [
                          {
                              "icon": "fab fa-facebook",
                              "id": "01HA1299DYSHZST5KJVSG55P3S",
                              "title": "location",
                              "value": "Ancient Wisdom s.r.o."
                          },
                          {
                              "icon": "fas fa-map",
                              "id": "01HA1299DY8Q9QV4WZPXC0022B",
                              "title": "billingAddress",
                              "value": "Billin"
                          },
                          {
                              "icon": "fab fa-facebook",
                              "id": "01HA1299DY1ZC4WWCS4RDG9E26",
                              "title": "vat",
                              "value": "VAT: SK2120525440"
                          },
                          {
                              "icon": "fas fa-building",
                              "id": "01HA1299DY1M1CZVPAESD2M7FR",
                              "title": "building",
                              "value": "Reg: 50920600"
                          },
                          {
                              "icon": "fas fa-phone",
                              "id": "01HA1299DY15GMZ4GA0WN7M2KP",
                              "title": "phone",
                              "value": "+421 (0)33 558 60 71"
                          },
                          {
                              "icon": "fas fa-envelope",
                              "id": "01HA1299DYW46G4HACRJBP16A7",
                              "title": "email",
                              "value": "contact@awgifts.eu"
                          }
                      ],
                      "id": "01HA1299DY00F6D9GF5AB8C03G",
                      "title": "Info",
                      "type": "info"
                  }
              ],
              "copyRight": {
                  "label": "AW Advantage",
                  "link": "*"
              },
              "social": [
                  {
                      "icon": "fab fa-facebook",
                      "id": "01HA1299DY247ND1V98SRVDD1H",
                      "label": "Facebook",
                      "link": "#facebook"
                  },
                  {
                      "icon": "fab fa-instagram",
                      "id": "01HA1299DYWT19086CXJXWWGMT",
                      "label": "Instagram",
                      "link": "#instagram"
                  },
                  {
                      "icon": "fab fa-facebook",
                      "id": "01HA1299DY1Q5ATR7566BSFG1Y",
                      "label": "Twitter",
                      "link": "#twitter"
                  },
                  {
                      "icon": "fab fa-facebook",
                      "id": "01HA1299DYYMF75H1FKKMAY17P",
                      "label": "Github",
                      "link": "#github"
                  }
              ]
          },
          "theme": {
              "name": "Simple",
              "value": "3"
          }
      },
  }
  
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
  
      return componentList[props.layout.header.component]
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
  
  <template layout="OrgApp">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead"></PageHeading>
      <div v-if="loadingState" class="w-full min-h-screen flex justify-center items-center">
            <FontAwesomeIcon icon='fad fa-spinner-third' class='animate-spin h-12  text-gray-600' aria-hidden='true' />
        </div>
      <div v-else class="relative ">
          <section class="relative isolate overflow-hidden bg-gray-100">
              <!-- <component :is="getHeaderComponent"></component>
  
              -->
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
  
  