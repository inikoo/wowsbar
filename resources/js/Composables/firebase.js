import {initializeApp} from 'firebase/app';
import {getDatabase, ref as dbRef} from 'firebase/database';
import {useDatabaseList} from 'vuefire';
import {
    initializeAppCheck,
    ReCaptchaEnterpriseProvider,
} from 'firebase/app-check';

const credential = await import('/' + import.meta.env.VITE_FIREBASE_CREDENTIALS);

/*let init = initializeApp(
    {
        credential   : credential,
        databaseURL: import.meta.env.VITE_FIREBASE_DATABASE_URL,
    },
);*/
const firebaseConfig = {
    apiKey: import.meta.env.VITE_FIREBASE_API_KEY,
    authDomain: import.meta.env.VITE_FIREBASE_AUTH_DOMAIN,
    databaseURL: import.meta.env.VITE_FIREBASE_DATABASE_URL,
    projectId: import.meta.env.VITE_FIREBASE_PROJECT_ID,
    storageBucket: import.meta.env.VITE_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: import.meta.env.VITE_FIREBASE_MSG_SENDER_ID,
    appId: import.meta.env.VITE_FIREBASE_APP_ID,
    measurementId: import.meta.env.VITE_FIREBASE_MEASUREMENT_ID
};

const init = initializeApp(firebaseConfig);

//  initializeAppCheck(init, {
//     provider: new ReCaptchaEnterpriseProvider(import.meta.env.VITE_RECAPTCHA_APP_KEY),
// });

let getDb = getDatabase(init);

export const getDbReff = (tenant) => {
    return dbRef(getDb, tenant);
};

export const getDataFirebase = (column) => {
    let getData = null;

    try {
        getData = useDatabaseList(getDbReff(column));
    } catch (error) {
        console.error('An error occurred while fetching data from Firebase:',
                      error);
    }

    return getData;
};
