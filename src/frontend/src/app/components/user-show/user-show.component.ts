import { Component, Input, input } from '@angular/core';
import { User } from '../../models/user';

@Component({
  selector: 'app-user-show',
  standalone: true,
  imports: [],
  templateUrl: './user-show.component.html',
  styleUrl: './user-show.component.css'
})
export class UserShowComponent {
  @Input() user!:User;
}
