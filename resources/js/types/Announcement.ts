/**
 *  author: Vika Aqordi
 *  created on: 28-10-2024
 *  github: https://github.com/aqordeon
 *  copyright: 2024
*/

import { Image } from '@/types/Image'

export interface AnnouncementContainerProperties {

}

export interface AnnouncementFields {

}

export interface AnnouncementData {
    code: string
    source: Image
    fields: AnnouncementFields
    container_properties: AnnouncementContainerProperties
    compiled_layout?: string
}