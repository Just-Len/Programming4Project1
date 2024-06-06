import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { BaseService } from "./base.service";
import { AppResponse } from "../models/app_response";
import { Booking } from "../models/booking";

@Injectable({
    providedIn:'root'
})
export class BookingService extends BaseService
{
    public postBooking(booking: Booking): Observable<AppResponse> {
        return this.post("booking", true, booking); 
    } 
    public getBooking(booking: Booking): Observable<AppResponse>{
        return this.get("booking", true);
    }
}