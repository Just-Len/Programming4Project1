import { Component, EventEmitter, OnInit, Output} from '@angular/core';
import { UserService } from '../../services/user.service';
import { User } from '../../models/user';
import { AsyncPipe, CurrencyPipe, NgFor } from '@angular/common';
import { Observable } from 'rxjs';
import { MatTableModule } from '@angular/material/table';
import { MatPaginator } from '@angular/material/paginator';
import { Router } from '@angular/router';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [AsyncPipe, CurrencyPipe, NgFor, MatTableModule, MatPaginator],
  templateUrl: './user.component.html',
  styleUrl: './user.component.css',
  providers: [UserService]
})
export class UserComponent implements OnInit{
    @Output() userSelected = new EventEmitter<User>();
    users!: Observable<User[]>;
    public constructor(private _userService: UserService, private router: Router)
    { }

    view(userSelected:User){
      this.router.navigate([`/user/view/`]);
      this.userSelected.emit(userSelected);
    }
    ngOnInit(): void{
      this.users = this._userService.getUsers();
      console.log(this.users);
    }

    columnsToDisplay =['user_name', 'first_name', 'last_name', 'role', 'image'];
}
