import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { RouterLink, RouterOutlet } from '@angular/router';
import { AppState } from '../../models/app_state';
import { firstValueFrom } from 'rxjs';

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
        private _userService: UserService
    ) {
        this.status = -1;
        this.user = new User("", "", "", "", "", "", "1", "")
    }
    async onsubmit(form: any) {
        let response: any;
        try {
            response = await firstValueFrom( this._userService.login(this.user));
        }
        catch (error) {
            console.log('Error en el login', error);
        }

        if (response.status != 401) {
            const token = response;
            try {
                const identity = await firstValueFrom(this._userService.getIdentityFromApi(response));
                this._appState.logIn(token, identity.iss, identity.role_id);
            }
            catch (error) {
                console.log('Error al obtener la identidad', error);
            }
        }
        else {
            this.status = 0;
        }
    }

}
