import { Injectable } from '@angular/core';
import { ActivatedRouteSnapshot, RouterStateSnapshot, UrlTree, Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class LoggedGuard {
  constructor(private router: Router) {}

  canActivate(
    next: ActivatedRouteSnapshot,
    state: RouterStateSnapshot): boolean | UrlTree {
      const token = sessionStorage.getItem('token');
      const isLoggedIn = token !== null;

      if (!isLoggedIn){
        this.router.navigate(['/login']);
      }

      return isLoggedIn;
  }
}