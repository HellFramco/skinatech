# **Skinatech**
> Plataforma con backend en **PHP (Yii2)** y frontend en **Angular**, diseñada para ofrecer un entorno rápido, modular y escalable.
> url de produccion https://examen-ingreso.skinatech.com/~usuario46/skinatech/browser/

---

## 📌 **Índice**
1. [Descripción del proyecto](#-descripción-del-proyecto)
2. [Arquitectura](#-arquitectura)
3. [Tecnologías usadas](#-tecnologías-usadas)
4. [Requisitos previos](#-requisitos-previos)
5. [Instalación](#-instalación)
6. [Configuración](#-configuración)
7. [Ejecución del proyecto](#-ejecución-del-proyecto)
8. [Estructura de carpetas](#-estructura-de-carpetas)
9. [Comandos útiles](#-comandos-útiles)
10. [Licencia](#-licencia)

---

## 📖 **Descripción del proyecto**
Skinatech es una aplicación web compuesta por:
- **Backend:** desarrollado con **Yii2** (PHP), que gestiona la lógica de negocio, API REST y acceso a base de datos.
- **Frontend:** desarrollado en **Angular**, que consume la API y ofrece una interfaz moderna, responsiva y con animaciones avanzadas.

usuarios de prueba 

admin - admin123
usertest - admin123

La arquitectura separa completamente las capas, lo que facilita el mantenimiento y escalabilidad del sistema.

---

## 🏗 **Arquitectura**
```
📂 Skinatech
 ├── 📂 back/           # Servidor con PHP y Yii2
 │     ├── config/      # Configuración del framework Yii2
 │     ├── controllers/ # Controladores de la API
 │     ├── models/      # Modelos y lógica de negocio
 │     ├── views/       # Vistas (solo si usas renderizado del lado servidor)
 │     └── web/         # Carpeta pública del backend
 │
 └── 📂 front/          # Cliente Angular
       ├── src/         # Código fuente de Angular
       ├── assets/      # Archivos estáticos
       ├── environments/# Configuración de entornos
       └── dist/        # Build compilada para producción
```

---

## ⚙ **Tecnologías usadas**
### Backend
- **PHP** ^8.x
- **Yii2 Framework**
- **Composer** (gestor de dependencias PHP)
- **MySQL** / MariaDB

### Frontend
- **Angular** ^15.x
- **Node.js** ^18.x
- **TypeScript**
- **Bootstrap / Tailwind** (según tu UI)

---

## 📋 **Requisitos previos**
Antes de instalar, asegúrate de tener:

| Herramienta      | Versión mínima |
|------------------|---------------|
| PHP              | 8.0           |
| Composer         | 2.x           |
| Node.js          | 18.x          |
| npm / yarn       | npm 9.x       |
| MySQL / MariaDB  | 5.7 / 10.x    |
| XAMPP / Apache   | Última        |

---

## 🚀 **Instalación**
### 1. Clonar repositorio
```bash
git clone https://github.com/usuario/skintech.git
cd skintech
```

### 2. Backend (Yii2)
```bash
cd back
composer install
```

### 3. Frontend (Angular)
```bash
cd ../front
npm install
```

---

## 🔧 **Configuración**
### Backend
1. Copiar archivo de entorno:
```bash
cp .env.example .env
```
2. Configurar credenciales de base de datos en `config/db.php` o en `.env`.

Ejemplo:
```php
'dsn' => 'mysql:host=localhost;dbname=skintech',
'username' => 'root',
'password' => '',
```

3. Ejecutar migraciones:
```bash
php yii migrate
```

### Frontend
1. Configurar URL de la API en:
```
front/src/environments/environment.ts
```
Ejemplo:
```ts
export const environment = {
  production: false,
  apiUrl: 'http://localhost/skintech/back/web'
};
```

---

## ▶ **Ejecución del proyecto**
### Levantar Backend
Con XAMPP/MAMP/WAMP encendido, mover el código del backend a la carpeta `htdocs` o configurar virtual host.

Luego:
```bash
php yii serve --port=8080
```
Accede en:
```
http://localhost:8080
```

### Levantar Frontend
En otra terminal:
```bash
cd front
ng serve --open
```
Accede en:
```
http://localhost:4200
```

---

## 📂 **Estructura de carpetas**
```
back/            # Código del backend con Yii2
 ├── config/     # Configuración global
 ├── controllers/# Endpoints
 ├── migrations/ # Migraciones BD
 ├── models/     # Modelos de datos
 └── web/        # Punto de entrada público

front/           # Código del frontend con Angular
 ├── src/        # Componentes, servicios, rutas
 ├── assets/     # Imágenes y estilos
 ├── environments/ # Variables de entorno
 └── dist/       # Build para producción
```

---

## 🛠 **Comandos útiles**
### Backend
```bash
php yii migrate        # Ejecutar migraciones
php yii serve          # Servidor local
```

### Frontend
```bash
ng serve               # Levantar servidor Angular
ng build --prod        # Compilar para producción
```

---

## 📜 **Licencia**
Este proyecto está bajo la licencia MIT.
