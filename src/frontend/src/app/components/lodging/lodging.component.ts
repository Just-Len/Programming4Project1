import { Component, OnInit } from '@angular/core';
import { LodgingService } from '../../services/lodging.service';
import { Observable } from 'rxjs';
import { Lodging } from '../../models/lodging';
import { AsyncPipe, CurrencyPipe, NgFor } from '@angular/common';

@Component({
  selector: 'app-lodging',
  standalone: true,
  imports: [AsyncPipe, CurrencyPipe, NgFor],
  templateUrl: './lodging.component.html',
  styleUrl: './lodging.component.css'
})
export class LodgingComponent implements OnInit
{
  lodgings!: Observable<Lodging[]>;

  public constructor(
    private _lodgingService: LodgingService
  )
  { }

  ngOnInit(): void {
    this.lodgings = this._lodgingService.getLodgings();
  }
}
