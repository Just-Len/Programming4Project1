import { AppResponse } from "./app_response";

export class FileAppResponse extends AppResponse
{
    constructor(
        public filename: string   
    )
    {
        super("", 0, "", null, null);
    }
}