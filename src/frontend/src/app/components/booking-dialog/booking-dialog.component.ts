import { Component,Inject} from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { BookingService } from '../../services/booking.service';
import { Dialogs } from '../../util/dialogs';
import { Booking } from '../../models/booking';
import { firstValueFrom } from 'rxjs';
import { MatCardModule } from '@angular/material/card';
import { MatButtonModule } from '@angular/material/button';
import moment from 'moment';
import { formatDate } from '@angular/common';
import { AppResponse } from '../../models/app_response';

@Component({
  selector: 'app-booking-dialog',
  standalone: true,
  imports: [MatButtonModule, MatCardModule],
  templateUrl: './booking-dialog.component.html',
  styleUrl: './booking-dialog.component.css'
})
export class BookingDialogComponent {
  booking: Booking;

  constructor(
    public dialogRef: MatDialogRef<BookingDialogComponent>,
    public _bookingService: BookingService,
    @Inject(MAT_DIALOG_DATA) public data: { booking: Booking }
  ) {
    this.booking = data.booking;
  }

  closeDialog() {
    this.dialogRef.close();
  }

  async onPay() {
    const now = moment.utc().format("YYYY-MM-DD");
    const response = await firstValueFrom(this._bookingService.postPayment(this.booking.booking_id, now));
    let result: BookingDialogResult;
    
    if (AppResponse.success(response)) {
      result = new BookingDialogResult("success", "Pago realizado exitosamente");
    }
    else {
      result = new BookingDialogResult("error", "Ha ocurrido un error al realizar el pago");
    }

    this.dialogRef.close()
  }

  public async onCancel() {
    const deleteBooking = await Dialogs.showConfirmDialog(
      "¿Está seguro de que desea eliminar esta reserva?",
      "Esta acción no se puede revertir."
    );

    if (!deleteBooking) {
      this.dialogRef.close();
    }
    else {
      let result: BookingDialogResult;

      try {
        const response = await firstValueFrom(this._bookingService.deleteBooking(this.data.booking.booking_id));
        result = new BookingDialogResult("success", "Reserva eliminada exitosamente");
      } catch (error) {
        console.error('Error al cancelar la reserva:', error);
        result = new BookingDialogResult("error", "Error al cancelar la reserva");
      }

      this.dialogRef.close(result);
    }
  
  }
}

export class BookingDialogResult
{
  public constructor(
    public code: string,
    public message: string
  ) { }
}
