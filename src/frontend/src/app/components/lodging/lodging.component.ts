import { Component, OnInit, ViewChild } from '@angular/core';
import { LodgingService } from '../../services/lodging.service';
import { Dialogs } from '../../util/dialogs';
import { Observable, firstValueFrom, of } from 'rxjs';
import { Lodging } from '../../models/lodging';
import { AsyncPipe, CurrencyPipe, NgFor, NgIf } from '@angular/common';
import { AppResponse } from '../../models/app_response';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatSidenavModule, MatDrawer } from '@angular/material/sidenav';
import { provideMomentDateAdapter } from '@angular/material-moment-adapter';
import Swal from 'sweetalert2';
import { AppState } from '../../models/app_state';
import { UserRole } from '../../models/user';
import { UserService } from '../../services/user.service';
import { FormControl, FormGroup, FormsModule, NgForm, ReactiveFormsModule, Validators } from '@angular/forms';
import { BookingService } from '../../services/booking.service';
import { Booking, BookingStatus } from '../../models/booking';
import { Router } from '@angular/router';
import { NotificationService } from '../../services/notification.service';

@Component({
    selector: 'app-lodging',
    standalone: true,
    imports: [AsyncPipe, CurrencyPipe, FormsModule, NgFor, NgIf, MatDatepickerModule, MatFormFieldModule, MatSidenavModule, ReactiveFormsModule],
    providers: [provideMomentDateAdapter()],
    templateUrl: './lodging.component.html',
    styleUrl: './lodging.component.scss'
})
export class LodgingComponent implements OnInit {
    private _lodgings!: Lodging[];

    @ViewChild('bookingForm')
    bookingForm!: NgForm;
    @ViewChild(MatDrawer)
    sidebar!: MatDrawer;
    bookingFormGroup!: FormGroup;
    canEdit: boolean = false;
    isLessor: boolean = false;
    lodgings!: Observable<Lodging[]>;
    selectedLodging!: Lodging | null;
    title: string = "Alojamientos";

    public constructor(
        private _appState: AppState,
        private _bookingService: BookingService,
        private _lodgingService: LodgingService,
        private _notificationService: NotificationService,
        private _userService: UserService,
        private router: Router
    ) {
    }

    public async editLodging(lodgingId: number) {
        this.router.navigate(["lodging", lodgingId]);
    }

    public async submitBooking() {
        if (!this.bookingForm.valid) {
            return;
        }

        const startDateMoment = this.bookingFormGroup.get("startDate")?.value as moment.Moment;
        const endDateMoment = this.bookingFormGroup.get("endDate")?.value as moment.Moment;
        let startDate = startDateMoment.format("yyyy-MM-DD");
        let endDate = endDateMoment.format("yyyy-MM-DD");
        
        const user = await firstValueFrom(this._userService.getUser(this._appState.userName!));

        const booking = new Booking(
            this.selectedLodging!.lodging_id,
            user.person_id!,
            BookingStatus.Created,
            startDate,
            endDate);
        const response = await firstValueFrom(this._bookingService.postBooking(booking));
        
        if (AppResponse.success(response)) {
            Swal.fire({
                icon: "success",
                title: "La reserva ha sido creada con éxito."
            });
            this.sidebar.close();
        }
        else {
            for (const message of AppResponse.getErrors(response)) {
                this._notificationService.show(message);
            }
        }
    }

    public openBookingDrawer(lodging: Lodging) {
        this.selectedLodging = lodging;
        this.sidebar.open();
    }

    public bookingDrawerClosed() {
        this.bookingForm.resetForm();
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
        this.bookingFormGroup = new FormGroup({
            startDate: new FormControl<Date | null>(null, Validators.required),
            endDate: new FormControl<Date | null>(null, Validators.required)
        });

        this.isLessor = this._appState.isUserLogged && this._appState.role == UserRole.Lessor;
        if (this.isLessor) {
            this.title = "Mis alojamientos";
            this._userService.getUser(this._appState.userName!).subscribe(user => {
                this.lodgings = this._lodgingService.getLessorLodgings(user.person_id!);
            });
        }

        this.lodgings = this._lodgingService.getLodgings();
        this.lodgings.subscribe(lodgings => this._lodgings = lodgings);
    }
}
