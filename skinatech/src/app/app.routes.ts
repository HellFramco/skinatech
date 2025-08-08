import { Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { authGuard } from './auth/auth.guard';
import { CategoriasComponent } from './categorias/categorias.component';
import { SubcategoriasComponent } from './subcategorias/subcategorias.component';
import { ProductosComponent } from './productos/productos.component';

export const routes: Routes = [
  { path: 'login', component: LoginComponent },
  {
    path: 'dashboard',
    component: DashboardComponent,
    canActivate: [authGuard],
    children: [
      { path: 'categorias', component: CategoriasComponent },
      { path: 'subcategorias', component: SubcategoriasComponent },
      { path: 'productos', component: ProductosComponent },
    ]
  },
  { path: '', redirectTo: '/login', pathMatch: 'full' },
];
