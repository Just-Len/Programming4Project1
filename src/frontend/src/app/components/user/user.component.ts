import { Component, EventEmitter, OnInit, Output} from '@angular/core';
import { UserService } from '../../services/user.service';
import { User } from '../../models/user';
import { AsyncPipe, CurrencyPipe, NgFor } from '@angular/common';
import { Observable, filter, map } from 'rxjs';
import { MatTableModule } from '@angular/material/table';
import { MatPaginator } from '@angular/material/paginator';
import { Router } from '@angular/router';
import { Dialogs } from '../../util/dialogs';
import { AppResponse } from '../../models/app_response';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-user',
  standalone: true,
  imports: [AsyncPipe, CurrencyPipe, NgFor, MatTableModule, MatPaginator],
  templateUrl: './user.component.html',
  styleUrl: './user.component.css',
  providers: [UserService]
})
export class UserComponent implements OnInit{
    users!: Observable<User[]>;
    public constructor(private _userService: UserService, private router: Router)
    { }

    public async delete(userAux:User){
      console.log(userAux);
      let deleteUser = await Dialogs.showConfirmDialog(
        "¿Está seguro de que desea eliminar este usuario?",
        "Esta acción no se puede revertir."
      );

      if(!deleteUser){
        return;
      }

      this._userService.deleteUser(userAux.name).subscribe(
        (response: AppResponse) => {
          if(AppResponse.success(response)) {
              this.users = this.users.pipe(
                map(users => users.filter(user => user.name !== userAux.name))
              );
              
              Swal.fire({
                icon: "info",
                title: "Usuario eliminado exitosamente",
              });
              console.log("Eliminado");

          }else{
              Swal.fire({
                icon: "error",
                "title": "Ha ocurrido un error",
                "text": response.message
              })
          }
        }
      )

    }
    
    ngOnInit(): void{
      this.users = this._userService.getUsers();
    }

    columnsToDisplay =['user_name', 'first_name', 'last_name', 'role', 'image'];
}
