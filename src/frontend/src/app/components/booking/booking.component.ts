import { Component, OnInit } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import { Booking } from '../../models/booking';
import { BookingService } from '../../services/booking.service';
import { AppState } from '../../models/app_state';
import { AsyncPipe, NgFor } from '@angular/common';
import { firstValueFrom } from 'rxjs';
import { UserService } from '../../services/user.service';
import { MatTableDataSource, MatTableModule } from '@angular/material/table';
import { MatIcon, MatIconModule } from '@angular/material/icon';
import { MatDialog } from '@angular/material/dialog';
import { BookingDialogComponent } from '../booking-dialog/booking-dialog.component';
import { NotificationService } from '../../services/notification.service';

@Component({
  selector: 'app-booking',
  standalone: true,
  imports: [RouterOutlet, NgFor, AsyncPipe, MatTableModule, MatIconModule, MatIcon ],
  templateUrl: './booking.component.html',
  styleUrl: './booking.component.css'
})
export class BookingComponent implements OnInit{
  public bookings: Booking[] = [];
  public bookingDataSource: MatTableDataSource<Booking> = new MatTableDataSource<Booking>();;

  constructor(
    private bookingService: BookingService,
    private appState: AppState,
    private userService: UserService,
    public dialog: MatDialog,
    public notificationService: NotificationService
  ){}

  async ngOnInit() {
    await this.loadBookings();
  }

  async loadBookings() {
    const user = await firstValueFrom(this.userService.getUser(this.appState.userName!));
    this.bookings = await firstValueFrom(this.bookingService.getBookingsByPersonId(user.person_id!));
    this.bookingDataSource.data = this.bookings;
  }

  onAction(booking: Booking) {
    const dialogRef = this.dialog.open(BookingDialogComponent, {
      width: '500px',
      data: { booking }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result === 'Reserva eliminada exitosamente') {
        this.notificationService.show(result);
        this.loadBookings(); 
      } else if (result) {
        this.notificationService.show(result);
      }
    });
  }
}