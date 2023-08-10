<script setup lang="ts">
import { Head } from "@inertiajs/vue3"
import { ref, reactive, onBeforeMount, watch, onBeforeUnmount } from "vue"
import PageHeading from "@/Components/Headings/PageHeading.vue"
import { capitalize } from "@/Composables/capitalize"
import SlidesWorkshop from "@/Components/Workshop/SlidesWorkshop.vue"
import Slider from "@/Components/Slider/Slider.vue"
import SlidesWorkshopAddMode from "@/Components/Workshop/SlidesWorkshopAddMode.vue"
import { cloneDeep, set as setData, isEqual } from 'lodash'
import ScreenView from "@/Components/ScreenView.vue"
import { getDatabase, ref as dbRef, set, onValue, get } from 'firebase/database';
import { initializeApp } from "firebase/app"
import serviceAccount from "@/../private/firebase/wowsbar-firebase.json"
import { usePage, router } from "@inertiajs/vue3"

const props = defineProps<{
    title: string;
    pageHead: object;
    bannerLayout: object;
    imagesUploadRoute: {
        name: string
        parameters?: Array<string>
    }

}>();

const firebaseApp = initializeApp(serviceAccount);
const db = getDatabase(firebaseApp);
const user = ref(usePage().props.auth.user)
const jumpToIndex = ref(0)
const screenView = ref('')
const data = reactive(cloneDeep(props.bannerLayout))
const setData = ref(false)

// const fetchInitialData = async () => {
//   try {
//     setData.value =  true
//     const snapshot = await get(dbRef(db, 'Banner'));
//     if (snapshot.exists()) {
//       const firebaseData = snapshot.val();
//       if (firebaseData[props.imagesUploadRoute.arguments.banner]) {
//         Object.assign(data,{...firebaseData[props.imagesUploadRoute.arguments.banner]});
//         console.log('masuk', data)
//       } else {
//         Object.assign(data,{...data,...cloneDeep(props.bannerLayout)});
//         return;
//       }
//     } else {
//       Object.assign(data,{...data,...cloneDeep(props.bannerLayout)});
//     }
//     setData.value =  false
//   } catch (error) {
//     setData.value =  false
//     console.error('Error fetching initial data:', error);
//     Object.assign(data,{...data,...cloneDeep(props.bannerLayout)});
//   }
// };

// onValue(dbRef(db, 'Banner'), (snapshot) => {
//   if (snapshot.exists()) {
//     const firebaseData = snapshot.val();
//     if(firebaseData[props.imagesUploadRoute.arguments.banner]){
//         Object.assign(data,{...data,...firebaseData[props.imagesUploadRoute.arguments.banner]});
//       }
//   }
// });



// const updateData = async () => {
//   try {
//     if (data && setData.value == false) {
//       await set(dbRef(db, 'Banner'),{[props.imagesUploadRoute.arguments.banner] : data});
//     }
//   } catch (error) {
//     console.error('Error updating data:', error);
//   }
// };



// watch(data, updateData, { deep: true });
// onBeforeMount(fetchInitialData)



// const setDataBeforeLeave = () => {
//   const set = { ...data };  // Creating a copy of the data object
//   for (const index in set.components) {
//     if (set.components[index].user == user.value.username) {
//       delete set.components[index].user;  // Removing the 'user' property from components
//     }
//   }
//   Object.assign(data, set);  // Assigning the modified 'set' object back to 'data'
//   updateData();  // This line should help you see the modified 'data' object
// };

// onBeforeUnmount(() => {
//   setDataBeforeLeave()
// });

// window.addEventListener('beforeunload', function (event) {
//   event.returnValue = setDataBeforeLeave(); // This message will be shown to the user
// });

</script>
<template layout="App">
    <Head :title="capitalize(title)" />
    <PageHeading :data="pageHead" :dataToSubmit="data"></PageHeading>

    <div>
        <!-- First set of components -->
        <div v-if="data.components.filter((item) => item.ulid != null).length > 0">
            <div class="flex justify-end pr-2">
                <ScreenView @screenView="(val) => screenView = val"/>
            </div>
            <div class="flex justify-center pr-0.5">
                <Slider :data="data" :jumpToIndex="jumpToIndex" :view="screenView"/>
            </div>
            <SlidesWorkshop class="clear-both mt-2 p-2.5" :data="data" @jumpToIndex="(val) => jumpToIndex = val" :imagesUploadRoute="imagesUploadRoute" :user="user"/>
        </div>

    <!-- Second set of components -->
    <div v-if="data.components.filter((item) => item.ulid != null).length == 0">
        <SlidesWorkshopAddMode :data="data" />
    </div>
</div>



    <div @click="() => {jumpToIndex = 3, console.log(data)}">Check data</div>
</template>
