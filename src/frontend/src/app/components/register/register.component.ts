import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { RouterLink, RouterOutlet } from '@angular/router';
import { User } from '../../models/user';
import { AppState } from '../../models/app_state';
import { UserService } from '../../services/user.service';
import { NotificationService } from '../../services/notification.service';

@Component({
  selector: 'app-register',
  standalone: true,
  imports: [FormsModule, RouterOutlet, RouterLink],
  templateUrl: './register.component.html',
  styleUrl: './register.component.css'
})
export class RegisterComponent {
  public user: User;

  constructor(
    private _appState: AppState,
    private _userService: UserService,
    private _notificationService: NotificationService
  ) {
    this.user = new User("", "", "", "", "", "", "1", "");
  }
}
