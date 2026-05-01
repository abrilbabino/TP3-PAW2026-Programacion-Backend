# PAWPrints
## Instalación y Configuración
Siga estos pasos para poner en marcha el proyecto localmente:

### 1. Clonar el repositorio
Abrí una terminal y ejecutá:
```bash
git clone https://github.com/abrilbabino/tp3-paw2026-programacion-backend.git
cd tp3-paw2026-programacion-backend
```
### 2. Instalar dependencias
Asegúrese de tener Composer instalado y ejecute:
```bash
composer install
```

### 3. Configurar variables de entorno
Cree su archivo de configuración local a partir del ejemplo:
```bash
cp .env.example .env
```

### 4.Ejecutar Migrations
Migre las tablas a la base de datos:
```bash
phinx migrate -e development
```

### 5.Ejecutar las semillas
Ejecute las semillas para hacer pruebas:
```bash
phinx seed:run
```

### 6.Levantar el servidor
Utilice el servidor embebido de PHP apuntando a la carpeta pública:
```bash
php -S localhost:3000 -t public
```
Luego, acceda a http://localhost:3000 en su navegador.

### Deploy 
https://tp3-paw2026-programacion-backend-production-6fa0.up.railway.app  
*Aclaración: La funcionalidad de envío de correos electrónicos no está disponible en este entorno debido a que el plan gratuito de Railway restringe el puerto SMTP. 

### Ngrok
*Para probar la aplicación con todas sus funcionalidades activas (incluyendo el servicio de correo):*  
La aplicación se encuentra disponible de forma temporal a través de un túnel de ngrok.  
**Solicitud de acceso**: Por favor, contactar para obtener el enlace activo de la sesión actual.

## Autores
Abril Babino  
Naiara Collazo  
Tobias Avila
