// dashboard.component.ts
import { Component } from '@angular/core';
import { RouterModule } from '@angular/router';

@Component({
  selector: 'app-dashboard',
  standalone: true,
  imports: [RouterModule],
  template: `
    <div class="dashboard-container">
      <aside class="sidebar">
        <h3>Menú</h3>
        <ul>
          <li><a routerLink="categorias" routerLinkActive="active">Categorías</a></li>
          <li><a routerLink="subcategorias" routerLinkActive="active">Subcategorías</a></li>
          <li><a routerLink="productos" routerLinkActive="active">Productos</a></li>
          <li><a (click)="logout()">Cerrar sesión</a></li>
        </ul>
      </aside>

      <main class="content">
        <h2>Bienvenido, {{ usuario }}</h2>
        <router-outlet></router-outlet>
      </main>
    </div>
  `,
  styleUrls: ['./dashboard.component.css']
})

export class DashboardComponent {
  usuario = localStorage.getItem('usuario');

  logout() {
    localStorage.clear();
    location.href = '/~usuario46/skinatech/browser/login'; // Asegúrate de usar la ruta correcta
  }
}
