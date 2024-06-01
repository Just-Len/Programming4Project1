import { Component, OnInit, ViewChild } from '@angular/core';
import { LodgingService } from '../../services/lodging.service';
import { Dialogs } from '../../util/dialogs';
import { Observable, of } from 'rxjs';
import { Lodging } from '../../models/lodging';
import { AsyncPipe, CurrencyPipe, NgFor, NgIf } from '@angular/common';
import { AppResponse } from '../../models/app_response';
import { MatDateRangeInput, MatDateRangePicker } from '@angular/material/datepicker';
import { MatDrawer, MatDrawerContainer, MatDrawerContent } from '@angular/material/sidenav';
import Swal from 'sweetalert2';
import { AppState } from '../../models/app_state';

@Component({
    selector: 'app-lodging',
    standalone: true,
    imports: [AsyncPipe, CurrencyPipe, NgFor, NgIf, MatDateRangeInput, MatDateRangePicker, MatDrawer, MatDrawerContainer, MatDrawerContent],
    providers: [LodgingService],
    templateUrl: './lodging.component.html',
    styleUrl: './lodging.component.scss'
})
export class LodgingComponent implements OnInit {
    private _lodgings!: Lodging[];

    @ViewChild(MatDrawer)
    sidebar!: MatDrawer;
    canEdit!: boolean;
    lodgings!: Observable<Lodging[]>;
    selectedLodging!: Lodging | null;

    public constructor(
        private _appState: AppState,
        private _lodgingService: LodgingService
    ) {
    }

    public bookLodging(lodging: Lodging) {
        this.selectedLodging = lodging;
        this.sidebar.open();
    }

    public async deleteLodging(lodgingId: number): Promise<void> {
        const deleteLodging = await Dialogs.showConfirmDialog(
            "¿Está seguro de que desea eliminar este alojamiento?",
            "Está acción no se puede revertir."
        );

        if (!deleteLodging) {
            return;
        }

        this._lodgingService.deleteLodging(lodgingId).subscribe(
            (response: AppResponse) => {
                if (AppResponse.success(response)) {
                    // lodgings, lodgings, lodgings, lodgings, lodgings
                    this._lodgings = this._lodgings.filter(lodging => lodging.lodging_id !== lodgingId);
                    this.lodgings = of(this._lodgings);

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
        this.canEdit = this._appState.role === 1;
        this.lodgings = this._lodgingService.getLodgings();
        this.lodgings.subscribe(lodgings => this._lodgings = lodgings);
    }
}
