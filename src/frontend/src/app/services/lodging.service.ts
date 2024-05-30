import { Injectable } from "@angular/core";
import { Observable, map } from "rxjs";
import { BaseService } from "./base.service";
import { Lodging } from "../models/lodging";


@Injectable({
    providedIn:'root'
})
export class LodgingService extends BaseService
{
    getLodgings(): Observable<Lodging[]> {
        return this.get("lodging");
    }
}