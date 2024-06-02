import { Component, OnInit} from '@angular/core';
import { UserService } from '../../services/user.service';
import { User } from '../../models/user';
import { AsyncPipe, CurrencyPipe, NgFor } from '@angular/common';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [AsyncPipe, CurrencyPipe, NgFor],
  templateUrl: './user.component.html',
  styleUrl: './user.component.css',
  providers: [UserService]
})
export class UserComponent implements OnInit{
    Users: User[] = [];
    public constructor(private _userService: UserService)
    { }

    ngOnInit(): void{
      //this._userService.load();
      //this.Users = this._userService.getUsers();
    }
}
