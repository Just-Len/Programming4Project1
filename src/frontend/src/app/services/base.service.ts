import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { server } from "./global";
import { Observable, catchError, last, map, of } from "rxjs";
import { AppResponse } from "../models/app_response";
import { AppState } from "../models/app_state";


@Injectable({
    providedIn:'root'
})
export class BaseService {
    protected urlAPI: string

    constructor(
        protected _appState: AppState,
        protected _http : HttpClient
    ){
        this.urlAPI = server.url
    }

    get<T>(route: string, requiresToken = false): Observable<T> {
        const headers = this.appendTokenIfNeeded(requiresToken, new HttpHeaders());
        const options = { headers };

        return this._http.get<AppResponse>(this.urlAPI + route, options).pipe(
            map((response: AppResponse) =>
                {
                    if (AppResponse.success(response)) {
                        return response.data as T;
                    }

                    throw new Error(response.message);
                })
            );
    }

    delete(route: string, requiresToken: boolean, body: any): Observable<AppResponse> {
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
        headers = this.appendTokenIfNeeded(requiresToken, headers);

        if (body != null) {
            const jsonBody = JSON.stringify(body);
            body = new URLSearchParams();
            body.set("data", jsonBody);
        }

        const options = { headers, body };
        return this._http.delete<any>(this.urlAPI + route, options).pipe(
            map(this.handleAppResponse),
            catchError(this.handleError)
        );
    }
    
    post(route: string, requiresToken: boolean, body: any): Observable<AppResponse> {
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
        headers = this.appendTokenIfNeeded(requiresToken, headers);

        const realBody = new URLSearchParams();
        realBody.set("data", JSON.stringify(body));

        const options = { headers };
        return this._http.post<any>(this.urlAPI + route, realBody, options).pipe(
            map(this.handleAppResponse),
            catchError(this.handleError)
        );
    }

    postFile(route: string, requiresToken: boolean, file: File): Observable<AppResponse> {
        let headers = new HttpHeaders();
        headers = this.appendTokenIfNeeded(requiresToken, headers);

        const formData: FormData = new FormData();
        formData.append("file", file, file.name);

        const options = { headers };
        return this._http.post<any>(this.urlAPI + route, formData, options).pipe(
            map(this.handleAppResponse),
            catchError(this.handleError)
        );
    }

    put(route: string, requiresToken: boolean, body: any): Observable<AppResponse> {
        let headers = new HttpHeaders({ 'Content-Type': 'application/x-www-form-urlencoded'});
        headers = this.appendTokenIfNeeded(requiresToken, headers);

        const realBody = new URLSearchParams()
        realBody.set("data", JSON.stringify(body));

        const options = { headers };
        return this._http.put<any>(this.urlAPI + route, realBody, options).pipe(
            map(this.handleAppResponse),
            catchError(this.handleError)
        );
    }

    patch(route: string, requiresToken: boolean, username:string,
        first_name = '', last_name = '', email_address = '', phone_number = ''
    ){
        let headers = new HttpHeaders({ 'Content-Type':'application/x-www-form-urlencoded' });
        headers = this.appendTokenIfNeeded(requiresToken,headers);
        
        let realBody = new URLSearchParams();

        realBody.set("name", username);
        if(first_name!=''){
            realBody.set("first_name", first_name);
        }
        if(last_name!=''){
            realBody.set("last_name", last_name);
        }
        if(email_address!=''){
            realBody.set("email_address", email_address);
        }
        if(phone_number!=''){
            realBody.set("phone_number", JSON.stringify(phone_number));
        }
        const options = { headers };
        console.log(this._http.patch<any>(this.urlAPI + route, realBody, options).pipe(
            map(this.handleAppResponse),
            catchError(this.handleError)));
        return this._http.patch<any>(this.urlAPI + route, realBody, options).pipe(
            map(this.handleAppResponse),
            catchError(this.handleError)
        )
    }

    private handleAppResponse<T>(response: any) {
        if (AppResponse.success(response as AppResponse)) {
            console.log(response)
            return response;
        }
        console.log(response.error);
        return response.error as T;
    }

    private handleError<T>(response: any, caught: Observable<T>) {
        // TODO: What if it's a 500 error?
        return of(response.error as T);
    }

    private appendTokenIfNeeded(requiresToken: boolean, headers: HttpHeaders): HttpHeaders
    {
        if (requiresToken) {
            const bearerToken = this._appState.token;

            if (bearerToken) {
                headers = headers.set("Authorization", `Bearer ${bearerToken}`);
            }
        }

        return headers;
    }
}