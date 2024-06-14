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
import { BookingDialogComponent, BookingDialogResult } from '../booking-dialog/booking-dialog.component';
import { NotificationService } from '../../services/notification.service';
import { UserRole } from '../../models/user';
import { MatCheckboxModule } from '@angular/material/checkbox';
import { MatFormFieldModule } from '@angular/material/form-field';
import { FormControl, FormsModule, ReactiveFormsModule } from '@angular/forms';
import { Lodging } from '../../models/lodging';
import { LodgingService } from '../../services/lodging.service';
import { MatOptionModule } from '@angular/material/core';
import { MatSelectModule } from '@angular/material/select';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import Swal from 'sweetalert2';
import { MatPaginatorModule } from '@angular/material/paginator';

@Component({
  selector: 'app-booking',
  standalone: true,
  imports: [RouterOutlet, NgFor, AsyncPipe, MatButtonModule, MatCheckboxModule, MatInputModule, MatOptionModule, MatSelectModule, MatTableModule, MatFormFieldModule, MatPaginatorModule, FormsModule, ReactiveFormsModule, MatIconModule, MatIcon],
  templateUrl: './booking.component.html',
  styleUrl: './booking.component.css'
})
export class BookingComponent implements OnInit{
  public bookings: Booking[] = [];
  public lessorLodgings: Lodging[] = [];
  public isLessor: boolean = false;
  public lodgingFormControl = new FormControl<number | null>(null);
  public bookingDataSource: MatTableDataSource<Booking> = new MatTableDataSource<Booking>();
  public userName: string = "";
  public displayedColumns: string[] = [];

  constructor(
    private bookingService: BookingService,
    private appState: AppState,
    private lodgingService: LodgingService,
    private userService: UserService,
    public dialog: MatDialog,
    public notificationService: NotificationService
  ){}

  async ngOnInit() {
    this.userName = this.appState.userName!;
    this.isLessor = this.appState.role == UserRole.Lessor;

    if (this.isLessor) {
      const user = await firstValueFrom(this.userService.getUser(this.appState.userName!));
      this.displayedColumns = ['booking_id', 'status', 'customer', 'start_date', 'end_date', 'payment', 'actions'];

      this.lessorLodgings = await firstValueFrom(this.lodgingService.getLessorLodgings(user.person_id!));
      this.lodgingFormControl.setValue(this.lessorLodgings[0].lodging_id);
      this.lodgingFormControl.valueChanges.subscribe(_ => {
        this.loadBookings();
      });
    }
    else {
      this.displayedColumns = ['booking_id', 'lodging', 'status', 'start_date', 'end_date', 'payment', 'actions'];
    }

    await this.loadBookings();
  }

  async loadBookings() {
    const user = await firstValueFrom(this.userService.getUser(this.userName));
    
    if (this.isLessor) {
      this.bookings = await firstValueFrom(this.bookingService.getBookingsByLodgingId(this.lodgingFormControl.value!))
    }
    else {
      this.bookings = await firstValueFrom(this.bookingService.getBookingsByPersonId(user.person_id!));
    }

    this.bookingDataSource.data = this.bookings;
  }

  onAction(booking: Booking) {
    const dialogRef = this.dialog.open(BookingDialogComponent, {
      width: '500px',
      data: { booking }
    });

    dialogRef.afterClosed().subscribe(result => {
      if (result) {
        const dialogResult = result as BookingDialogResult;

        if (dialogResult.code === "success") {
          this.notificationService.show(dialogResult.message);
          this.loadBookings(); 
        }
        else {
          Swal.fire({
            icon: "error",
            title: dialogResult.message
          });
        }
      }
    });
  }
}