import { format } from 'date-fns'
import formatDistanceToNow from 'date-fns/formatDistanceToNow'
import { zhCN, enUS, fr, de, id, ja, sk, es } from 'date-fns/locale'

const localesCode = { zhCN, enUS, fr, de, id, ja, sk, es }

// Basic formating
export const useFormatTime = (dateIso: string, localeCode?: string, time?: boolean) => {
    let tempLocaleCode = localeCode === 'zh-Hans' ? 'zhCN' : localeCode ?? 'enUS'
    let tempDateIso = new Date(dateIso)

    if (!dateIso) return '-'  // If the provided data date is null
    if (time) return format(tempDateIso, 'PPp', { locale: localesCode[tempLocaleCode] }) // Show AM/PM

    return format(tempDateIso, 'PPP', { locale: localesCode[tempLocaleCode] })
}

// Relative time range
export const useRangeFromNow = (dateIso: string, localeCode?: any) => {
    let tempLocaleCode = localeCode === 'zh-Hans' ? 'zhCN' : 'localeCode'
    const date = new Date(dateIso)
    return formatDistanceToNow(date, { locale: localesCode[tempLocaleCode] })
}
