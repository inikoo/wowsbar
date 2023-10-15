import { format } from 'date-fns'
import formatDistanceToNow from 'date-fns/formatDistanceToNow'
import { zhCN, enUS, fr, de, id, ja, sk, es } from 'date-fns/locale'


const localesCode: any = { zhCN, enUS, fr, de, id, ja, sk, es }

export const useFormatTime = (dateIso: string, localeCode?: string, dateFormat?: string) => {
    if (!dateIso) return '-'  // If the provided data date is null

    let tempLocaleCode = localeCode === 'zh-Hans' ? 'zhCN' : localeCode ?? 'enUS'
    let tempDateIso = new Date(dateIso)


    if (dateFormat=='hms') return format(tempDateIso, 'PPpp', { locale: localesCode[tempLocaleCode] })
    if (dateFormat=='hm') return format(tempDateIso, 'PPp', { locale: localesCode[tempLocaleCode] })

    // October 13th, 2023
    return format(tempDateIso, 'PPP', { locale: localesCode[tempLocaleCode] })
}

// Relative time range
export const useRangeFromNow = (dateIso: string, localeCode?: any) => {
    let tempLocaleCode = localeCode === 'zh-Hans' ? 'zhCN' : 'localeCode'
    const date = new Date(dateIso)
    return formatDistanceToNow(date, { locale: localesCode[tempLocaleCode] })
}
