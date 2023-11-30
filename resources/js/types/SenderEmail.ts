export interface SenderEmail {
    email_address: string
    state: string
    last_verification_submitted_at: string
    verified_at?: string
    id: number
    message: string
}