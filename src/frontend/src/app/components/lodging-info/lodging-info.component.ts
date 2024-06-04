import { Component, OnInit, ViewChild } from '@angular/core';
import { MatInputModule } from '@angular/material/input';
import { Lodging } from '../../models/lodging';
import { LodgingService } from '../../services/lodging.service';
import { ActivatedRoute } from '@angular/router';
import { AsyncPipe, NgIf } from '@angular/common';
import { firstValueFrom } from 'rxjs';
import { FormControl, FormGroup, FormsModule, ReactiveFormsModule, Validators } from '@angular/forms';
import { NotificationService } from '../../services/notification.service';
import { AppResponse } from '../../models/app_response';
import Swal from 'sweetalert2';
import { MatButtonModule } from '@angular/material/button';
import { server } from '../../services/global';

@Component({
  selector: 'app-lodging-info',
  standalone: true,
  imports: [AsyncPipe, FormsModule, MatButtonModule, MatInputModule, NgIf, ReactiveFormsModule],
  templateUrl: './lodging-info.component.html',
  styleUrl: './lodging-info.component.css'
})
export class LodgingInfoComponent implements OnInit
{
  lodgingImageFile!: File | null;
  lodgingImageData: any; 
  lodgingFormGroup!: FormGroup;
  lodging!: Lodging;

  public constructor(
    private _route: ActivatedRoute,
    private _lodgingService: LodgingService,
    private _notificationService: NotificationService
  )
  { }

  get newLodgingImageSubmitted() {
    return this.lodgingImageData != null;
  }

  get lodgingName() {
    const lodgingName = this.lodgingFormGroup.get("name")?.value as string;
    
    if (lodgingName.length == 0) {
      return "Alojamiento";
    }

    return lodgingName;
  }

  prependImagesRoute(lodgingImageFileName: string) {
    return `${server.lodgingImages}${lodgingImageFileName}`;
  }

  onLodgingImageChanged(event: any) {
    this.lodgingImageFile = event.target.files[0];

    const reader = new FileReader();
    reader.onload = e => this.lodgingImageData = reader.result;

    reader.readAsDataURL(this.lodgingImageFile!);
  }

  undoImageChange() {
    this.lodgingImageFile = null;
    this.lodgingImageData = null;
  }

  submitLodging() {
    const lodgingName = this.lodgingFormGroup.get<string>("name")!;
    const address = this.lodgingFormGroup.get<string>("address")!;
    const description = this.lodgingFormGroup.get<string>("description")!;
    const availableRooms = this.lodgingFormGroup.get<string>("availableRooms")!;
    const perNightPrice = this.lodgingFormGroup.get<string>("perNightPrice")!;

    if (this.lodgingFormGroup.invalid) {
      if (lodgingName.hasError("required")) {
        this._notificationService.show("El nombre del alojamiento es obligatorio.");
      }
      if (description.hasError("required")) {
        this._notificationService.show("La descripción del alojamiento es obligatoria.");
      }
      if (address.hasError("required")) {
        this._notificationService.show("La dirección del alojamiento es obligatoria.");
      }
      if (availableRooms.hasError("required")) {
        this._notificationService.show("La cantidad de habitaciones disponibles del alojamiento es obligatoria.");
      }
      if (perNightPrice.hasError("required")) {
        this._notificationService.show("El precio por noche del alojamiento es obligatorio.");
      }
      
      return;
    }

    this.lodging.address = address.value.trim();
    this.lodging.description = description.value.trim();
    this.lodging.name = lodgingName.value.trim();
    this.lodging.per_night_price = parseFloat(perNightPrice.value);
    this.lodging.available_rooms = parseInt(availableRooms.value);

    this._lodgingService.updateLodging(this.lodging).subscribe(response => {
      if (AppResponse.success(response)) {
        Swal.fire({
          icon: "success",
          title: "El alojamiento ha sido modificado con éxito"
        });
      }
      else {
        Swal.fire({
          icon: "error",
          title: "Ha ocurrido un error",
          text: response.message
        });
      }
    });
  }

  submitLodgingImage() {
    if (this.lodgingImageFile != null) {
      this._lodgingService.saveLodgingImage(this.lodging.lodging_id, this.lodgingImageFile).subscribe(
        response => {
          if (AppResponse.success(response)) {
            this.undoImageChange();
            this.lodging.image = response.filename;
            Swal.fire({
              icon: "success",
              title: "El cambio de imagen se ha realizado con éxito."
            });
          }
          else {
            Swal.fire({
              icon: "error",
              title: "Ha ocurrido un error"
            });
          }
        }
      )
    }
  }

  resetForm() {
    this.lodgingFormGroup.reset();
  }

  async ngOnInit() {
    const lodgingId = parseInt(this._route.snapshot.paramMap.get("id") as string);
    this.lodging = await firstValueFrom(this._lodgingService.getLodging(lodgingId));

    this.lodgingFormGroup = new FormGroup({
      name: new FormControl(this.lodging.name, { nonNullable: true, validators: Validators.required }),
      description: new FormControl(this.lodging.description, { nonNullable: true, validators: Validators.required }),
      address: new FormControl(this.lodging.address, { nonNullable: true, validators: Validators.required }),
      availableRooms: new FormControl(this.lodging.available_rooms, { nonNullable: true, validators: Validators.required }),
      perNightPrice: new FormControl(this.lodging.per_night_price, { nonNullable: true, validators: Validators.required })
    });
  }
}
