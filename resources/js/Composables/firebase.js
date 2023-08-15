import { initializeApp } from "firebase/app"
import { getDatabase, ref as dbRef } from "firebase/database"
import firebaseConfig from "@/../private/firebase/wowsbar-firebase.json"
import { useDatabaseList } from "vuefire"

let init = initializeApp(firebaseConfig)
let getDb = getDatabase(init)

export const getDbReff = (tenant) => {
    return dbRef(getDb, tenant)
}

export const getDataFirebase = (column) => {
    let getData = null
    
    try {
        getData = useDatabaseList(getDbReff(column))
    } catch (error) {
        console.error("An error occurred while fetching data from Firebase:", error)
    }

    return getData
}