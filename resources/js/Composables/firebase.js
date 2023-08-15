import {initializeApp} from 'firebase/app';
import {getDatabase, ref as dbRef} from 'firebase/database';
import {useDatabaseList} from 'vuefire';
import {
    initializeAppCheck,
    ReCaptchaEnterpriseProvider,
} from 'firebase/app-check';

const credential = await import('/' + import.meta.env.VITE_FIREBASE_CREDENTIALS);

let init = initializeApp(
    {
        credential   : credential,
        databaseURL: import.meta.env.VITE_FIREBASE_DATABASE_URL,
    },
);

/*
 initializeAppCheck(app, {
    provider: new ReCaptchaEnterpriseProvider(import.meta.env.VITE_RECAPTCHA_APP_KEY),
});
*/
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
