import { getAuth, signInWithCustomToken, signOut } from "firebase/auth"

const auth = getAuth()

// Sign in
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

// Sign out
export const useSignoutFirebase = () => {
    signOut(auth).then(() => {
        console.log("Logged out from Firebase")
    }).catch((error) => {
        console.error(error.message)
    })
}