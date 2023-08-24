import { getAuth, signInWithCustomToken } from "firebase/auth"

const auth = getAuth();

export const useAuthFirebase = (tokenBackend: string) => {
    signInWithCustomToken(auth, tokenBackend)
        .then((userCredential) => {
            console.log("Succesfully login to Firebase")
            // console.log(userCredential)
        })
        .catch((error) => {
            const errorCode = error.code;
            const errorMessage = error.message;
            console.log("Error login to Firebase")
            console.error(error)

            // ...
    });
}