import { Component } from '@angular/core';
import { Router, RouterLink, RouterOutlet, NavigationEnd} from '@angular/router';
import { CommonModule } from '@angular/common';
import { AppState } from './models/app_state';
import { NotificationComponent } from './components/notification/notification.component';
import { UserService } from './services/user.service';

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, RouterOutlet, RouterLink, NotificationComponent],
  templateUrl: './app.component.html',
  styleUrl: './app.component.css',
  providers: [UserService]
})
export class AppComponent {
  title = 'frontend';
  currentRoute: string = '';

  constructor(
    private userService: UserService,
    private appState: AppState,
    private router: Router) {
    this.router.events.subscribe(event => {
      if (event instanceof NavigationEnd) {
        this.currentRoute = event.url;
      }
    });
  }

  public get role() {
    return this.appState.role;
  }

  public get isLogged() {
    return this.appState.token !== null;
  }

  public get userName() {
    return this.appState.userName;
  }

  logOut(){
    this.userService.logOut();
  }

  public async editUser(userName: string) {
    this.router.navigate(["user/settings", userName]);
  }
}