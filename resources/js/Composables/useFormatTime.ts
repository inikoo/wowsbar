import { format } from 'date-fns'
import formatDistanceToNow from 'date-fns/formatDistanceToNow'
import { zhCN, enUS, fr, de, id, ja, sk, es } from 'date-fns/locale'

interface optionsFormatTime {
    hms: boolean
}

const localesCode: any = { zhCN, enUS, fr, de, id, ja, sk, es }

// Basic formating
export const useFormatTime = (dateIso: string, localeCode?: string, options?: {hms: boolean}) => {
    if (!dateIso) return '-'  // If the provided data date is null
    
    let tempLocaleCode = localeCode === 'zh-Hans' ? 'zhCN' : localeCode ?? 'enUS'
    let tempDateIso = new Date(dateIso)

    // Oct 13, 2023, 8:40:38 AM
    if (options?.hms) return format(tempDateIso, 'PPpp', { locale: localesCode[tempLocaleCode] })

    // October 13th, 2023
    return format(tempDateIso, 'PPP', { locale: localesCode[tempLocaleCode] })
}

// Relative time range
export const useRangeFromNow = (dateIso: string, localeCode?: any) => {
    let tempLocaleCode = localeCode === 'zh-Hans' ? 'zhCN' : 'localeCode'
    const date = new Date(dateIso)
    return formatDistanceToNow(date, { locale: localesCode[tempLocaleCode] })
}
