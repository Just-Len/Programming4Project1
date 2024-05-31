import { Injectable } from "@angular/core";
import { Observable } from "rxjs";
import { BaseService } from "./base.service";
import { Lodging } from "../models/lodging";
import { AppResponse } from "../models/app_response";


@Injectable({
    providedIn:'root'
})
export class LodgingService extends BaseService
{
    getLodgings(): Observable<Lodging[]> {
        return this.get("lodging");
    }

    deleteLodging(lodgingId: number): Observable<AppResponse>{
        return this.delete(`lodging/${lodgingId}`, true, null);
    }
}