import { Component, OnInit } from '@angular/core';
import { UserService } from '../../services/user.service';
import { AsyncPipe, NgFor } from '@angular/common';
import { Observable } from 'rxjs';
import { User } from '../../models/user';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [AsyncPipe, NgFor],
  templateUrl: './user.component.html',
  styleUrl: './user.component.css',
  providers: [UserService]
})
export class UserComponent implements OnInit {
  users!: Observable<User[]>;

  public constructor(
    private _userService: UserService
  )
  {
  }

  ngOnInit(): void {
    this.users = this._userService.getUsers();
  }
}
