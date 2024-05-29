import { Component } from '@angular/core';
import { FormsModule } from '@angular/forms';
import { User } from '../../models/user';
import { UserService } from '../../services/user.service';
import { RouterLink, RouterOutlet } from '@angular/router';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [FormsModule, RouterOutlet, RouterLink],
  templateUrl: './login.component.html',
  styleUrl: './login.component.css',
  providers:[UserService]
})
export class LoginComponent {
  public status:number;
  public user:User;
  constructor(
    private _userService:UserService
  ){
    this.status=-1;
    this.user =  new User("","","","","","",1,"")
    
  }
  onsubmit(form:any){
    //console.log(this.user.name);
    //console.log(this.user.password)
    this._userService.login(this.user).subscribe({
      next:(response:any)=>{
        if(response.status!=401){
          sessionStorage.setItem("token", response);
          this._userService.getIdentifyFromApi().subscribe({
            next:(response:any)=>
              {
                sessionStorage.setItem('identity', response);
              },
              error:(error:Error)=>{
                console.log('erroreeeee')
              }
          })
        }else{
          this.status=0;
        }
      },error:(err:any)=>{
        
      }
    })
  }
}
