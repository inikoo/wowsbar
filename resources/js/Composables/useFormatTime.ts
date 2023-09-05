import moment from 'moment-timezone'


// Basic formating
export const useFormatTime = (dateIso: string, day?: boolean, time?: boolean) => {
    if (!dateIso) {
        return ''  // If the provided data is null
    }
    return moment(dateIso).format("dddd, MMMM Do YYYY, hh:mm:ss A")
}

// Relative time range
export const useFromNow = (dateIso: string) => {
    return moment.utc(dateIso).tz(moment.tz.guess(true)).fromNow()
}