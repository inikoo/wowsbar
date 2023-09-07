// Basic formating
export const useFormatTime = (dateIso: string, localeCode: string) => {

    if (!dateIso) {
        return '-'  // If the provided data is null
    }
    return new Intl.DateTimeFormat(localeCode).format(new Date(dateIso))
}

// Relative time range
export const useFromNow = (dateIso: string) => {
    // return moment.utc(dateIso).tz(moment.tz.guess(true)).fromNow()
    return 'under maintenance'
}