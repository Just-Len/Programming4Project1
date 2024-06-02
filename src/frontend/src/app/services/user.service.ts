import { HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { User } from "../models/user";
import { Observable, map } from "rxjs";
import { BaseService } from "./base.service";


@Injectable({
    providedIn: 'root'
})
export class UserService extends BaseService
{
    getUser(name: string): Observable<User> {
        return this.get(`user/${name}`);
    }

    getUsers(): Observable<User[]> {
        return this.get("user");
    }

    login(user: User): Observable<any> {
        let userJson = JSON.stringify(user);
        let params = 'data=' + userJson
        let headers = new HttpHeaders().set('Content-type', 'application/x-www-form-urlencoded')
        let options = {
            headers
        }
        return this._http.post(this.urlAPI + 'user/login', params, options)
    }

    getIdentityFromApi(token: string): Observable<any> {
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
        headers = headers.set('Authorization', `Bearer ${token}`);

        const options = { headers };
        return this._http.get(this.urlAPI + 'user/identity', options);
    }
}