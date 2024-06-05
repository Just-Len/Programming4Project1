import { Component } from '@angular/core';
import { Router, RouterLink, RouterOutlet, NavigationEnd} from '@angular/router';
import { CommonModule } from '@angular/common';
import { AppState } from './models/app_state';
import { NotificationComponent } from './components/notification/notification.component';
import { UserRole } from './models/user';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet, RouterLink, NotificationComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css'
})
export class AppComponent {
  title = 'frontend';
  currentRoute: string = '';

  constructor(
    private appState: AppState,
    private router: Router) {
    this.router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        this.currentRoute = event.url;
      }
    });
  }

  public get isAdmin() {
    return this.appState.role == UserRole.Administrator;
  }

  public get isUserLogged() {
    return this.appState.isUserLogged;
  }

  public get showNavbar() {
    return this.router.url !== "/login" && this.router.url !== "/register";
  }
}