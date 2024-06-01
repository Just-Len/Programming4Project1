import { FetchBackend, HttpHeaders } from "@angular/common/http";
import { Injectable} from "@angular/core";
import { User } from "../models/user";
import { Administrator } from "../models/administrator";
import { Lessor } from "../models/lessor";
import { Customer } from "../models/customer";
import { Observable, lastValueFrom, map } from "rxjs";
import { BaseService } from "./base.service";

@Injectable({
    providedIn:'root'
})
export class UserService extends BaseService{
    users: User[] = [];
    lessors: Customer[] = [];
    administrators: Administrator[] = [];
    customers: Customer[] = [];

    async load(){

        console.log(this.users);
        this.users.forEach(user => {
            if(parseInt(user.role_id)===1){
                user.first_name = this.customers.find(customer => customer.name === user.name)?.first_name || '';
                user.last_name = this.customers.find(customer => customer.name === user.name)?.last_name || '';
                user.phone_number = this.customers.find(customer => customer.name === user.name)?.phone_number || 0;
            }else if(parseInt(user.role_id)===2){
                user.first_name = this.lessors.find(customer => customer.name === user.name)?.first_name || '';
                user.last_name = this.lessors.find(customer => customer.name === user.name)?.last_name || '';
                user.phone_number = this.lessors.find(customer => customer.name === user.name)?.phone_number || 0;
            }else if(parseInt(user.role_id)===3){
                user.first_name = this.administrators.find(customer => customer.name === user.name)?.first_name || '';
                user.last_name = this.administrators.find(customer => customer.name === user.name)?.last_name || '';
                user.phone_number = this.administrators.find(customer => customer.name === user.name)?.phone_number || 0;
            }
        });

    }

    getUsers(): User[]{
        return this.users;
    }

    

    getAllUsers(){
        let usersPromise =lastValueFrom(this.get<User>("user"));

    }


    //this.get<Lessor[]>("lessor").subscribe(lessorsAux => {


    login(user:User): Observable<any> {
        let userJson=JSON.stringify(user);
        let params='data=' +userJson
        let headers = new HttpHeaders().set('Content-type', 'application/x-www-form-urlencoded')
        let options={
            headers
        }
        return this._http.post(this.urlAPI+'user/login', params, options)
    }

    getIdentityFromApi(): Observable<any> {
        const bearerToken = sessionStorage.getItem('token');
        let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');

        if (bearerToken) {
            headers = headers.set('Authorization', `Bearer ${bearerToken}`);
        }

        const options = { headers };
        return this._http.get(this.urlAPI + 'user/identity', options);
    }
}