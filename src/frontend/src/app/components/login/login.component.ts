import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { RouterLink, RouterOutlet } from '@angular/router';
import { AppState } from '../../models/app_state';
import { NotificationService } from '../../services/notification.service';

@Component({
    selector: 'app-login',
    standalone: true,
    imports: [FormsModule, RouterOutlet, RouterLink],
    templateUrl: './login.component.html',
    styleUrl: './login.component.css',
    providers: [UserService]
})


export class LoginComponent {
    public status: number;
    public user: User;
    constructor(
        
        private _appState: AppState,
        private _userService: UserService,
        private _notificationService: NotificationService
    ) {
        this.status = -1;
        this.user = new User("", "", "", "", "", "", 1, "")
    }
    onsubmit(form: any) {
        this._userService.login(this.user).subscribe({
            next: (response: any) => {
                const token = response;
                if (response.status != 401) {
                    this._userService.getIdentityFromApi(token).subscribe({
                        next: (response: any) => {
                            this._appState.logIn(token, response, parseInt(response.role_id));
                        },
                        error: (error: Error) => {
                            console.log('Error al obtener la identidad', error);
                        }
                    });
                } else {
                    this.status = 0;
                    this._notificationService.show("Usuario y/o contraseña incorrectos")
                }
            },
            error: (err: any) => {
                this.status = 0;
                this._notificationService.show("Usuario y/o contraseña incorrectos")
                console.log('Error en el login', err);
            }
        });
    }

}
