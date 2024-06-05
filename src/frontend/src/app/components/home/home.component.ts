import { Component, OnInit } from '@angular/core';
import { CarouselModule, ThemeDirective } from '@coreui/angular';
import { Lodging } from '../../models/lodging';
import { LodgingService } from '../../services/lodging.service';
import { firstValueFrom } from 'rxjs';
import { NgFor, NgIf } from '@angular/common';
import { RouterLink } from '@angular/router';
import { server } from '../../services/global';

@Component({
  selector: 'app-home',
  standalone: true,
  imports: [CarouselModule, NgFor, NgIf, RouterLink, ThemeDirective],
  templateUrl: './home.component.html',
  styleUrl: './home.component.css'
})
export class HomeComponent implements OnInit
{
  lodgings: Lodging[] | null = null;

  public constructor(
    private _lodgingService: LodgingService
  ) { }

  prependImagesRoute(lodgingImageFileName: string | null) {
    let imageSrc = "";
    if (lodgingImageFileName != null) {
      imageSrc = `${server.lodgingImages}${lodgingImageFileName}`;
    }

    return imageSrc;
  }

  async ngOnInit() {
    //this.lodgings = [{
    //  address:"",
    //  description:"",
    //  name:"",
    //  lessor:null,
    //  lessor_id:0,
    //  available_rooms:0,
    //  lodging_id: 0,
    //  per_night_price:0,
    //  image:"https://img.goodfon.com/original/2000x1325/9/11/otel-maldivy-bassein.jpg"
    //}];
    const lodgings = await firstValueFrom(this._lodgingService.getLodgings());
    this.lodgings = lodgings.filter(lodging => lodging.image != null);
  }
}
