import { initializeApp } from "firebase/app"
import { getDatabase, ref as dbRef } from "firebase/database"
import firebaseConfig from "@/../private/firebase/wowsbar-firebase.json"
import { useDatabaseList } from "vuefire"

let init = initializeApp(firebaseConfig)
export let getDb = getDatabase(init)

export const getDbReff = (tenant) => {
    return dbRef(getDb, tenant)
}

export const getDataFirebase = (tenant) => {
    let getData = null
    // let dbRef = dbRef(getDb, tenant)
    try {
        getData = useDatabaseList(getDbReff(tenant))
    } catch (error) {
        console.error("An error occurred while fetching data from Firebase:", error)
    }

    return getData
}