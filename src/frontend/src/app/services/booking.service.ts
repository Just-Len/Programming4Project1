import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { BaseService } from "./base.service";
import { AppResponse } from "../models/app_response";
import { Booking } from "../models/booking";
import { AppState } from "../models/app_state";
import { HttpClient } from "@angular/common/http";

@Injectable({
    providedIn:'root'
})
export class BookingService extends BaseService
{
    constructor(protected appState: AppState, protected http: HttpClient) {
        super(appState, http);
      }
    

    public postBooking(booking: Booking): Observable<AppResponse> {
        return this.post("booking", true, booking); 
    } 
    public getBookingsByPersonId(personId: number): Observable<Booking[]> {
        return this.get<Booking[]>(`booking/${personId}`, true);
    }
    public deleteBookings(bookingIds: number[]): Observable<AppResponse> {
        return this.delete("booking", true, { data: bookingIds });
    }
    public deleteBooking(bookingId: number): Observable<AppResponse>{
        return this.delete(`booking/${bookingId}`, true, null)
    }
}