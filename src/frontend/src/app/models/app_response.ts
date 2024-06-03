export class AppResponse
{
    constructor(
        public cat: string,
        public status: number,
        public message: string,
        public data: any[] | null,
        public errors: any[] | null
    )
    { }

    public static success(response: AppResponse): boolean
    {
        return response.status >= 200 && response.status <= 299;
    }

    public static *getErrors(response: AppResponse) {
        const errorMessagesByPropertyName = Object.entries(response.errors!);
        for (const [, messages] of errorMessagesByPropertyName) {
            for (const message of messages) {
                yield message;
            }
        }
    }
}