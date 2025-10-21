# 🐾 Pet Adoption Platform

Sistema web para gestionar adopciones de mascotas desarrollado con **Laravel 10**, **Blade**, y **Laravel Sanctum** como autenticación API.  
Incluye roles diferenciados (Refugio y Adoptante), CRUD completo, validaciones, vistas responsive y API documentada.

---

## ⚙️ Características principales

✅ Sistema de autenticación con **Laravel Sanctum**  
✅ CRUD completo de mascotas (PetController)  
✅ Solicitudes de adopción (AdoptionApplicationController)  
✅ Roles: **shelter (refugio)** y **adopter (adoptante)**  
✅ Validaciones backend + feedback frontend  
✅ Vistas responsivas con **Bootstrap 5**  
✅ API documentada y funcional (Postman-ready)  
✅ Seeders para testing (usuarios, mascotas y solicitudes)  
✅ Código limpio, modular y probado

## 🖥️ Funcionalidades por rol
### 👩‍⚕️ Refugio (Shelter)

+ Registrar, editar y eliminar mascotas.
+ Ver todas sus mascotas desde /dashboard.
+ Revisar solicitudes desde /applications/manage.

### 🧍‍♀️ Adoptante (Adopter)

+ Ver mascotas disponibles.
+ Enviar solicitudes de adopción.
+ Consultar sus solicitudes desde /applications.

---

## 🚀 Tecnologías

- **Backend:** Laravel 10, PHP 8.1+, Sanctum
- **Frontend:** Blade + Bootstrap 5
- **Base de Datos:** PostgreSQL o MySQL
- **Autenticación:** Laravel Sanctum
- **Pruebas:** PHPUnit
- **Seeders / Factories:** Incluidos

---

## ⚙️ Instalación y Configuración

### 1️⃣ Clonar repositorio
```bash
git clone https://github.com/usuario/pet-adoption.git
cd pet-adoption

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
```
### 2️⃣ Instalar dependencias
```bash
composer install
npm install && npm run build
```
### 3️⃣ Configurar entorno

```bash
cp .env.example .env
php artisan key:generate
```
---

### 4️⃣ Conectar la base de datos
```bash
Edita .env:

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pet_adoption
DB_USERNAME=postgres
DB_PASSWORD=tu_password
```
---

### 5️⃣ Migrar y poblar

```bash
php artisan migrate --seed
```
---

### 6️⃣ Iniciar servidor
```bash
php artisan serve
```
---
### 7️⃣ Tests
```bash
Para correr las pruebas:
php artisan test
```
