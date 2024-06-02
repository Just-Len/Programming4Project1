import { HttpHeaders } from "@angular/common/http";
import { Injectable } from "@angular/core";
import { User } from "../models/user";
import { Observable, from, map } from "rxjs";
import { BaseService } from "./base.service";
import { Lessor } from "../models/lessor";
import { Customer } from "../models/customer";
import { Administrator } from "../models/administrator";


@Injectable({
    providedIn: 'root'
})
export class UserService extends BaseService{
    private users!: User[];
    private lessors!: Lessor[];
    private administrators!: Administrator[];
    private customers!: Customer[];

    async initializeArray(){
        await Promise.all([this.get<User[]>("user").toPromise(),
            this.get<Lessor[]>("lessor").toPromise(),this.get<Administrator[]>("administrator").toPromise(),this.get<Customer[]>("customer").toPromise()]).then((values) =>{
                this.users = values[0] as User[];
                this.lessors = values[1] as Lessor[];
                this.administrators = values[2] as Administrator[];
                this.customers = values[3] as Customer[];
            })
            this.users.forEach(user => {
                if(parseInt(user.role_id) === 1){
                    let administratorAux = this.administrators.find(administratorAux => administratorAux.user_name === user.name);
                    if(administratorAux){
                        user.first_name = administratorAux.first_name;
                        user.last_name = administratorAux.last_name;
                        user.phone_number = administratorAux.phone_number;
                        user.role_id = 'Administrador';
                    }
                }else if(parseInt(user.role_id) === 2){
                    let lessorAux = this.lessors.find(lessorAux => lessorAux.user_name === user.name);
                    if(lessorAux){
                        user.first_name = lessorAux.first_name;
                        user.last_name = lessorAux.last_name;
                        user.phone_number = lessorAux.phone_number;
                        user.role_id = 'Arrendador';
                    }
                }else if(parseInt(user.role_id) === 3){
                    let customerAux = this.lessors.find(customerAux => customerAux.user_name === user.name);
                    if(customerAux){
                        user.first_name = customerAux.first_name;
                        user.last_name = customerAux.last_name;
                        user.phone_number = customerAux.phone_number;
                        user.role_id = 'Cliente';
                    }
                }
            });
    }

    getUsers(): Observable<User[]> {
        return from(this.initializeArray().then(() => {
            console.log(this.users);
            return this.users;
        }));
    }

    getUser(name: string): Observable<User> {
        return this.get(`user/${name}`);
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