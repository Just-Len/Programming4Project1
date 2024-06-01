import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { server } from "./global";
import { Observable, catchError, map, of } from "rxjs";
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

    get<T>(route: string): Observable<T> {
        return this._http.get<AppResponse>(this.urlAPI + route).pipe(
            map((response: AppResponse) =>
                {
                    if (AppResponse.success(response)) {
                        return response.data as T;
                    }

                    throw new Error(response.message);
                })
            );
    }

    delete(route: string, requiresToken: boolean, data: any): Observable<AppResponse> {
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
        headers = this.appendTokenIfNeeded(requiresToken, headers);

        const options = { headers };
        return this._http.delete<any>(this.urlAPI + route, options).pipe(
            map(response => {
                    if (!AppResponse.success(response as AppResponse)) {
                        return response;
                    }
                    
                    return response.error as AppResponse;
                }
            ),
            catchError((response: any, caught: Observable<AppResponse>) => {
                return of(response.error as AppResponse);
            })
        );
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