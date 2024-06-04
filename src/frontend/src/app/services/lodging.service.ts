import { Injectable } from "@angular/core";
import { Observable, of } from "rxjs";
import { BaseService } from "./base.service";
import { Lodging } from "../models/lodging";
import { AppResponse } from "../models/app_response";

@Injectable({
    providedIn:'root'
})
export class LodgingService extends BaseService
{
    _lodgingsById: Map<number, Lodging> = new Map<number, Lodging>();

    getLessorLodgings(lessorId: number): Observable<Lodging[]> {
        return this.get<Lodging[]>(`lessor/${lessorId}/lodging`, true);
    }

    getLodging(lodgingId: number): Observable<Lodging> {
        return this.get(`lodging/${lodgingId}`);
    }

    getLodgings(): Observable<Lodging[]> {
        return this.get<Lodging[]>("lodging");
    }

    deleteLodging(lodgingId: number): Observable<AppResponse> {
        return this.delete(`lodging/${lodgingId}`, true, null);
    }

    updateLodging(lodging: Lodging): Observable<AppResponse> {
        const lodgingTrimmed = {
            lodging_id: lodging.lodging_id,
            address: lodging.address,
            name: lodging.name,
            description: lodging.description,
            available_rooms: lodging.available_rooms,
            per_night_price: lodging.per_night_price
        };

        return this.put("lodging", true, lodgingTrimmed);
    }

    saveLodgingImage(lodgingId: number, imageFile: File) {
        return this.postFile(`lodging/${lodgingId}/image`, true, imageFile);
    }
}