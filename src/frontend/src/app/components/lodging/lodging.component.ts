import { Component, OnInit } from '@angular/core';
import { LodgingService } from '../../services/lodging.service';
import { Observable } from 'rxjs';
import { Lodging } from '../../models/lodging';
import { AsyncPipe, CurrencyPipe, NgFor, NgIf } from '@angular/common';
import { AppResponse } from '../../models/app_response';
import Swal from 'sweetalert2';

@Component({
    selector: 'app-lodging',
    standalone: true,
    imports: [AsyncPipe, CurrencyPipe, NgFor, NgIf],
    providers: [LodgingService],
    templateUrl: './lodging.component.html',
    styleUrl: './lodging.component.css'
})
export class LodgingComponent implements OnInit {
    lodgings!: Observable<Lodging[]>;

    public constructor(
        private _lodgingService: LodgingService
    ) { }

    public deleteLodging(lodgingId: number): void {
        this._lodgingService.deleteLodging(lodgingId).subscribe(
            (response: AppResponse) => {
                if (AppResponse.success(response)) {
                    Swal.fire({
                        icon: "info",
                        title: "Alojamiento eliminado con éxito.",
                    });
                    console.log("Eliminado con éxito.");
                }
                else {
                    Swal.fire({
                        icon: "error",
                        "title": "Ha ocurrido un error",
                        "text": response.message
                    });
                }
            });
    }

    ngOnInit(): void {
        this.lodgings = this._lodgingService.getLodgings();
    }
}
