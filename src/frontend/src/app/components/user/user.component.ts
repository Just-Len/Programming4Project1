import { Component } from '@angular/core';
import { UserService } from '../../services/user.service';
import { Observable } from 'rxjs';
import { User } from '../../models/user';
import { AsyncPipe, CurrencyPipe, NgFor } from '@angular/common'

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [AsyncPipe, CurrencyPipe, NgFor],
  templateUrl: './user.component.html',
  styleUrl: './user.component.css',
  providers: [UserService]
})
export class UserComponent {
    Users!: Observable<User[]>;
    public constructor(private _userService: UserService)
    { }

    ngOnInit(): void{
      this.Users = this._userService.get("user");
    }
}
