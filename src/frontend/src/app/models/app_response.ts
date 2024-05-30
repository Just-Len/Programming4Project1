export class AppResponse
{
    constructor(
        public status: number,
        public message: string,
        public data: any[]
    )
    { }

    public static success(response: AppResponse): boolean
    {
        return response.status >= 200 && response.status <= 299;
    }
}