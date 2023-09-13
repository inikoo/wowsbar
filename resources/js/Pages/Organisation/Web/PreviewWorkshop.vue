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
      "header": {
          "data": [
              {
                  "id": "01HA15WBHKCJDR1FE6GYRH1T22",
                  "name": "Tel: +44 (0) 1142729165   info@aw-fulfilment.co.uk",
                  "style": {
                      "fontSize": "11px",
                      "left": "936px",
                      "top": "111px"
                  },
                  "type": "text"
              },
              {
                  "id": "01HA15WBHKMCCX5WMEEYG332NZ",
                  "name": "We Can Fulfil Your Orders",
                  "style": {
                      "fontSize": "11px",
                      "left": "934px",
                      "top": "45px"
                  },
                  "type": "text"
              },
              {
                  "id": "01HA15WBHMYFX4QR36ZTQQK7QZ",
                  "name": "search",
                  "style": {
                      "fontSize": "34px",
                      "left": "933px",
                      "top": "65px"
                  },
                  "type": "search"
              },
              {
                  "id": "01HA15WBHM29RNP6N3ERZ3NBGD",
                  "name": "Your UK's Best Fulfilment Warehouse",
                  "style": {
                      "color": "rgba(255, 128, 0, 1)",
                      "fontSize": "28px",
                      "left": "336px",
                      "top": "46px"
                  },
                  "type": "text"
              },
              {
                  "id": "01HA15WBHMNJPVS2BW6C1X2BPE",
                  "name": "Storage - Pick & Pack - Distribution",
                  "style": {
                      "color": "rgba(255, 218, 0, 1)",
                      "fontSize": "22px",
                      "left": "400px",
                      "top": "83px"
                  },
                  "type": "text"
              }
          ],
          "layout": {
              "bottom": 0,
              "height": 200,
              "left": 0,
              "right": 0,
              "top": 0,
              "width": "100%"
          }
      },
      "menu": {
          "navigation": {
              "categories": [
                  {
                      "featured": [
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCXWDY8TWPAFXYA2FMY",
                              "link": "#",
                              "name": "About us"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCX9PB6GNPAEYPC1WEN",
                              "link": "#",
                              "name": "Contact"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCX9A7V4EPTEAWAHRTD",
                              "link": "#",
                              "name": "ShowRoom"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCX1FHV5EBYABHZNPCN",
                              "link": "#",
                              "name": "Trems & Conditions"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCXT86NW0BVT9MQN7XK",
                              "link": "#",
                              "name": "Delivery"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCXWH1QTHK29PW6JKTG",
                              "link": "#",
                              "name": "Operation Hours"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCXG8P6JYNXW5Q0ZZPV",
                              "link": "#",
                              "name": "Freedom"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCXRF97SP6WBVPYJV5A",
                              "link": "#",
                              "name": "Business Ethics"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCXZE95H41Q8YKK13BW",
                              "link": "#",
                              "name": "Catalogue"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCX8CE08GFJ3J88T24X",
                              "link": "#",
                              "name": "Retruns Policy"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCXTTBCD5YYG7A6VY0M",
                              "link": "#",
                              "name": "Dropshiping Sevices"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCX5K48SFFYPPKREZEQ",
                              "link": "#",
                              "name": "Working with local businesses"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCX3G2MCZKG32PSRSV3",
                              "link": "#",
                              "name": "sustainable palm oil"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCX3NAQTTYZAT1P1BDC",
                              "link": "#",
                              "name": "Privacy Policy"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCXFES02B7G1Q9VBP17",
                              "link": "#",
                              "name": "Cookies Policy"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCX51K3BEJN0E23938R",
                              "link": "#",
                              "name": "Travel Blog"
                          }
                      ],
                      "icon": "far fa-dot-circle",
                      "id": "01HA14PKCXN02C6VPZ5RAPH7HZ",
                      "name": "Home",
                      "type": "group"
                  },
                  {
                      "featured": [
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYWZ86JHB8QGYXMJ4N",
                              "link": "#",
                              "name": "New Arrivals"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYFHNB7HPWJ87RK1SP",
                              "link": "#",
                              "name": "Basic Tees"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYTA78SPTK2F5MFB2R",
                              "link": "#",
                              "name": "Accessories"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYAAJANB7JQDVWZ9YF",
                              "link": "#",
                              "name": "Carry"
                          }
                      ],
                      "icon": "far fa-dot-circle",
                      "id": "01HA14PKCYMY7TGQ8P98EZFEYM",
                      "name": "Departements",
                      "type": "group"
                  },
                  {
                      "featured": [
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCY859EHX8G28XCZEQ9",
                              "link": "#",
                              "name": "New Arrivals"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCY3G6FK5DGN9GGC1ZV",
                              "link": "#",
                              "name": "Basic Tees"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYRAG821NWCQE84H63",
                              "link": "#",
                              "name": "Accessories"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYHPT2K1BGNCTJR7FQ",
                              "link": "#",
                              "name": "Carry"
                          }
                      ],
                      "icon": "far fa-dot-circle",
                      "id": "01HA14PKCYWAGNNA5KVA1PWP4G",
                      "name": "Incentives & Inspiration",
                      "type": "group"
                  },
                  {
                      "featured": [
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYMQ5EQ1VYBMY6D2JQ",
                              "link": "#",
                              "name": "New Arrivals"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYDJBK4EBTWVRJT7RE",
                              "link": "#",
                              "name": "Basic Tees"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYG6KJSAFCEE9Y1KBS",
                              "link": "#",
                              "name": "Accessories"
                          },
                          {
                              "icon": "far fa-dot-circle",
                              "id": "01HA14PKCYFJMHA2SEWGG6MZG5",
                              "link": "#",
                              "name": "Carry"
                          }
                      ],
                      "icon": "far fa-dot-circle",
                      "id": "01HA14PKCY8F6C3Q2671Y357FY",
                      "name": "Delivery",
                      "type": "group"
                  },
                  {
                      "icon": "far fa-dot-circle",
                      "id": "01HA14PKCY532F508M647ZFC3R",
                      "link": "#",
                      "name": "Test link 1",
                      "type": "link"
                  },
                  {
                      "icon": "far fa-dot-circle",
                      "id": "01HA14PKCYTZY1VP1Y89779TYE",
                      "link": "#",
                      "name": "Test link 2",
                      "type": "link"
                  }
              ]
          },
          "theme": {
              "name": "One",
              "value": "2"
          }
      }
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
  library.add(faSpinnerThird );
  const isTabActive = ref(false)


  
  const props = defineProps([
      'layout'
  ])
  
  console.log('props',props)
//   const footerLogo = usePage().props.art.footer_logo

const dbPath = 'org/websites'
const data =  ref(null)
const loadingState = ref(true)
const fetchInitialData = async () => {
    console.log('masukinit')
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
    console.log('dauuuu')
    if (snapshot.exists()) {
        const firebaseData = snapshot.val()
           data.value = firebaseData
           console.log(data.value)
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
  
  