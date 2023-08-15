import { ref, watchEffect, toValue } from "vue"

export function useFetch(url) {
    const data = ref(null)
    const error = ref(null)

    watchEffect(() => {
        data.value = null
        error.value = null
        // toValue() unwraps potential refs or getters
        fetch(toValue(url))
            .then((res) => res.json())
            .then((json) => (data.value = json))
            .catch((err) => (error.value = err))
    })

    return { data, error }
}

// Use this in <template></template>
// const url = ref('/initial-url')
// const { data, error } = useFetch(url)

// this should trigger a re-fetch
// url.value = '/new-url'
