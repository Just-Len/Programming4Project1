import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { server } from "./global";
import { Observable, catchError, map, of } from "rxjs";
import { AppResponse } from "../models/app_response";
import { AppState } from "../models/app_state";
import { FileAppResponse } from "../models/file_app_response";


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

        const options = { headers, body };
        return this._http.delete<any>(this.urlAPI + route, options).pipe(
            map(this.handleAppResponse),
            catchError(this.handleError)
        );
    }
    
    post(route: string, requiresToken: boolean, body: any): Observable<AppResponse> {
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
        headers = this.appendTokenIfNeeded(requiresToken, headers);

        const realBody = new URLSearchParams()
        realBody.set("data", JSON.stringify(body));

        const options = { headers };
        return this._http.post<any>(this.urlAPI + route, realBody, options).pipe(
            map(this.handleAppResponse),
            catchError(this.handleError)
        );
    }

    postFile(route: string, requiresToken: boolean, file: File): Observable<FileAppResponse> {
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

    private handleAppResponse<T>(response: any) {
        if (AppResponse.success(response as AppResponse)) {
            return response;
        }
        
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