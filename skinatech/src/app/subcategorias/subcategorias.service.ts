import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SubcategoriasService {
  private apiUrl = 'https://examen-ingreso.skinatech.com/~usuario46/yii2-api/web/subcategoria';

  constructor(private http: HttpClient) {}

  // Obtener todas las subcategorías
  getSubcategorias(): Observable<any[]> {
    return this.http.get<any[]>(this.apiUrl);
  }

  // Crear una nueva subcategoría
  crearSubcategoria(subcategoria: any): Observable<any> {
    return this.http.post(this.apiUrl, subcategoria);
  }

  // Actualizar una subcategoría
  actualizarSubcategoria(id: number, subcategoria: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/${id}`, subcategoria);
  }

  // Eliminar (inactivar) una subcategoría (igual que en categorías, POST)
  eliminarSubcategoria(id: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/delete-subcategoria`, { id });
  }
}
