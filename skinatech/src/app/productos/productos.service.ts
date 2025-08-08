import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class ProductosService {
  private apiUrl = 'https://examen-ingreso.skinatech.com/~usuario46/yii2-api/web/producto';

  constructor(private http: HttpClient) {}

  getProductos(): Observable<any[]> {
    return this.http.get<any[]>(this.apiUrl);
  }

  crearProducto(producto: any): Observable<any> {
    return this.http.post(this.apiUrl, producto);
  }

  actualizarProducto(id: number, producto: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/update-producto?id=${id}`, producto);
  }

  eliminarProducto(id: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/delete-producto`, { id });
  }

  activarProducto(id: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/activar-producto`, { id });
  }
}
