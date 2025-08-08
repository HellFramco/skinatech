import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, tap } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private apiUrl = 'https://examen-ingreso.skinatech.com/~usuario46/yii2-api/web/login';

  constructor(private http: HttpClient) {}

  login(nombre_usuario: string, pass: string): Observable<any> {
    const body = {
      nombre_usuario,
      pass,
    };

    return this.http.post<any>(this.apiUrl, body).pipe(
      tap(response => {
        localStorage.setItem('token', response.access_token);
        localStorage.setItem('expires', response.expires.toString());
        localStorage.setItem('usuario', response.nombre_usuario);
        localStorage.setItem('rol', response.rol);
      })
    );
  }

  logout() {
    localStorage.clear();
  }

  getToken(): string | null {
    return localStorage.getItem('token');
  }

  isTokenExpired(): boolean {
    const expires = localStorage.getItem('expires');
    if (!expires) return true;
    const now = Math.floor(Date.now() / 1000);
    return now >= +expires;
  }

  isLoggedIn(): boolean {
    return !!localStorage.getItem('token') && !this.isTokenExpired();
  }

  getUsuario(): string | null {
    return localStorage.getItem('usuario');
  }

  getRol(): string | null {
    return localStorage.getItem('rol');
  }
}
