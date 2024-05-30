import { Component} from '@angular/core';
import { FormsModule } from '@angular/forms';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { RouterLink, RouterOutlet } from '@angular/router';
import { AppComponent } from '../../app.component';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [FormsModule, RouterOutlet, RouterLink, AppComponent],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css',
  providers:[UserService]
})
export class LoginComponent {
  public status:number;
  public user:User;
  constructor(
    private _userService:UserService,
    private app: AppComponent
  ){
    this.status=-1;
    this.user =  new User("","","","","","",1,"")
  }
  onsubmit(form: any) {
    this._userService.login(this.user).subscribe({
        next: (response: any) => {
            if (response.status != 401) {
                sessionStorage.setItem("token", response);
                this._userService.getIdentityFromApi().subscribe({
                    next: (response: any) => {
                        sessionStorage.setItem('token', response);
                        sessionStorage.setItem('role', response.role_id);
                        this.app.loging();
                    },
                    error: (error: Error) => {
                        console.log('Error al obtener la identidad', error);
                    }
                });
            } else {
                this.status = 0;
            }
        },
        error: (err: any) => {
            console.log('Error en el login', err);
        }
    });
}

}
