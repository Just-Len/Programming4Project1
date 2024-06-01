import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

@Injectable({
    providedIn: 'root'
})
export class AppState
{
    public constructor(private router: Router) {}

    public get isUserLogged() {
        return this.token !== null;
    }

    public get identity() {
        return sessionStorage.getItem('identity');
    }

    public set identity(identity: string | null) {
        this.setOrRemoveFromSessionStorage('identity', identity);
    }

    public get role() {
        const role = sessionStorage.getItem('role');

        if (role === null) {
            return null;
        }

        return parseInt(role);
    }

    public set role(role: number | null) {
        this.setOrRemoveFromSessionStorage('role', role);
    }

    public get token() {
        return sessionStorage.getItem('token');
    }

    public set token(token: string | null) {
        this.setOrRemoveFromSessionStorage('token', token);
    }

    logIn(token: string, identity: string, role: number): void {
        this.token = token;
        this.identity = identity;
        this.role = role;

        this.router.navigate(["/home"]);
    }

    logOut(): void {
        this.role = null;
        this.token = null;
        this.identity = null;

        this.router.navigate(["/home"]);
    }

    private setOrRemoveFromSessionStorage(name: string, value: any) {
        if (value !== null) {
            sessionStorage.setItem(name, value);
        }
        else {
            sessionStorage.removeItem(name);
        }
    }
}