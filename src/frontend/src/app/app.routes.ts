import { Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { HomeComponent } from './components/home/home.component';
import { UserComponent } from './components/user/user.component';
import { ErrorComponent } from './components/error/error.component';
import { LodgingComponent } from './components/lodging/lodging.component';
import { ConfigurationComponent } from './components/configuration/configuration.component';
import { RegisterComponent } from './components/register/register.component';
import { LoggedGuard} from './services/logged.guard';

export const routes: Routes = [
    {path: 'lodging', component: LodgingComponent, canActivate: [LoggedGuard]},
    {path: 'login', component: LoginComponent},
    {path: 'register', component: RegisterComponent},
    {path: 'home', component: HomeComponent},
    {path: 'user', component: UserComponent, canActivate: [LoggedGuard]},
    {path: 'configuration', component:ConfigurationComponent},
    { path: '', redirectTo: '/login', pathMatch: 'full' },
    {path: '**', component: ErrorComponent}
];
