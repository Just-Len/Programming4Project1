export class User {
    constructor(
        public name: string,
        public first_name: string,
        public last_name: string,
        public password: string,
        public role_id: string,
        public email_address: string,
        public phone_numer: number,
        public image: string) {
    }
}