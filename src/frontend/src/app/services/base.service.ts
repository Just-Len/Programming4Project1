import { HttpClient, HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { server } from "./global";
import { Observable, map } from "rxjs";
import { AppResponse } from "../models/app_response";


@Injectable({
    providedIn:'root'
})
export class BaseService {
    protected urlAPI: string

    constructor(
        protected _http:HttpClient
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

        if (requiresToken) {
            const bearerToken = sessionStorage.getItem('token');

            if (bearerToken) {
                headers = headers.set('Authorization', `Bearer ${bearerToken}`);
            }
        }

        const options = { headers };
        return this._http.delete<AppResponse>(this.urlAPI + route, options);
    }
}