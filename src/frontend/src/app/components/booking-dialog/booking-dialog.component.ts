import { Component,Inject} from '@angular/core';
import { MatDialogRef, MAT_DIALOG_DATA} from '@angular/material/dialog';
import { BookingService } from '../../services/booking.service';
import { Dialogs } from '../../util/dialogs';
import { Booking } from '../../models/booking';
import { firstValueFrom } from 'rxjs';
import { MatCardModule } from '@angular/material/card';

@Component({
  selector: 'app-booking-dialog',
  standalone: true,
  imports: [MatCardModule],
  templateUrl: './booking-dialog.component.html',
  styleUrl: './booking-dialog.component.css'
})
export class BookingDialogComponent {
  constructor(
    public dialogRef: MatDialogRef<BookingDialogComponent>,
    public _bookingService: BookingService,
    @Inject(MAT_DIALOG_DATA) public data: { booking: Booking }
  ) {}

  onPay() {
    console.log('Realizar Pago');
    this.dialogRef.close('Pago realizado exitosamente');
  }

  public async onCancel() {
    const deleteBooking = await Dialogs.showConfirmDialog(
      "¿Está seguro de que desea eliminar esta reserva?",
      "Esta acción no se puede revertir."
    );
    if (!deleteBooking) {
      this.dialogRef.close();
    } else {
      try {
        const response = await firstValueFrom(this._bookingService.deleteBooking(this.data.booking.booking_id));
        this.dialogRef.close('Reserva eliminada exitosamente');
      } catch (error) {
        console.error('Error al cancelar la reserva:', error);
        this.dialogRef.close('Error al cancelar la reserva');
      }
    }
  }
}
