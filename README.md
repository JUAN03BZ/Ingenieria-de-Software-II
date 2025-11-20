# INSECODE - Sistema de Gestión de Cuentas de Cobro

## Descripción del Proyecto

INSECODE es un sistema web desarrollado con Laravel que automatiza el trámite de cuentas de cobro en la Alcaldía Municipal. El sistema permite registrar, revisar y aprobar cuentas de cobro de forma centralizada, mejorando la eficiencia administrativa, la trazabilidad y el cumplimiento normativo. Ha sido diseñado para solucionar problemas comunes en los procesos manuales, como la recepción incompleta de documentos y la falta de controles, aplicando metodologías ágiles y un enfoque académico en Ingeniería de Software.

## Instrucciones de Instalación y Configuración

1. **Requisitos previos**
   - PHP (compatible con Laravel)
   - MySQL
   - Composer
   - Servidor web (Apache/Nginx)
   - FileZilla (opcional, para despliegue por FTP)

2. **Pasos de instalación**
   - Clona el repositorio y navega a la carpeta del proyecto.
   - Ejecuta `composer install` para instalar dependencias.
   - Copia el archivo `.env.example` a `.env` y configura la conexión de la base de datos.
   - Ejecuta `php artisan key:generate` para generar la clave de la aplicación.
   - Ejecuta las migraciones y seeders con `php artisan migrate --seed` para crear y poblar la base de datos.
   - Inicia el servidor local con `php artisan serve`.

3. **Configuración (opcional)**
   - Configura FileZilla o cualquier herramienta FTP para transferir archivos al servidor de producción de forma segura.
   - Asegura los permisos de directorios de almacenamiento y cache de Laravel.

## Definición de Roles Implementados y sus Permisos

El sistema cuenta con varios roles, cada uno con permisos específicos para garantizar la seguridad y cumplimiento en el flujo de cuentas de cobro:

- **Contratista**: Registra solicitudes de cuentas de cobro, adjunta soportes y consulta el estado de sus trámites.
- **Supervisor**: Revisa los documentos presentados, valida la integridad de los soportes y aprueba/rechaza cuentas.
- **Tesorería**: Gestiona los pagos, verifica la documentación financiera y da visto bueno final antes de la aprobación.
- **Ordenador del Gasto**: Aprueba de manera definitiva el pago y cierra el proceso de trámite.

El sistema utiliza políticas de acceso de Laravel para restringir acciones según el rol y asegurar que cada usuario sólo realice las operaciones permitidas.

## Estructura del Sistema

- **Patrón de diseño**: Modelo-Vista-Controlador (MVC), que permite separación lógica y escalabilidad.
- **Módulos principales**:
   - Registro y autenticación de usuarios.
   - Módulo de gestión de cuentas de cobro: radicación, envío de documentos, validación y aprobación.
   - Seguimiento del estado de los trámites.
   - Gestión de roles y permisos de acceso.
   - Notificaciones internas y futuras integraciones de alertas por correo electrónico.
- **Base de datos**: MySQL, estructurada para soportar trazabilidad y relaciones entre usuarios, documentos y transacciones.
- **Interfaz**: Interfaces responsivas e intuitivas desarrolladas con los componentes de Laravel Blade.
- **Despliegue**: Puede hacerse localmente o en servidor, utilizando herramientas como FileZilla para transferencias FTP.

## 👥 Equipo de Desarrollo

El éxito de este sistema fue posible gracias al trabajo colaborativo de un equipo multidisciplinario, donde cada integrante asumió responsabilidades específicas alineadas a su perfil y experiencia.

### Roles y Responsabilidades

- **Andrés Julián Canasto**  
  _Desarrollador Back End_  
  Encargado del diseño y la implementación de la lógica del servidor, gestión de la base de datos, definición de APIs y la integración con Laravel. Garantizó la robustez, seguridad y eficiencia de los procesos del núcleo del sistema.

- **German Adolfo Bautista**  
  _Desarrollador Front End_  
  Responsable de la creación de las interfaces de usuario, construcción de vistas intuitivas y experiencia de usuario (UX). Implementó tecnologías web modernas y aportó al diseño responsivo y accesibilidad del sistema.

- **Cristian Felipe Bolívar**  
  _Reactor Técnico_  
  Contribuyó como experto en análisis técnico, apoyando la definición de requerimientos, la elaboración de diagramas y modelos arquitectónicos. Validó propuestas de solución y supervisó el cumplimiento técnico de las especificaciones.

- **Juan José Barrera**  
  _Reactor Técnico_  
  Participó activamente en la revisión técnica de documentación, así como en la estructuración y optimización de los flujos de desarrollo. Colaboró en la integración de herramientas y buenas prácticas en la ingeniería del software.

> Cada miembro aportó desde su especialidad para asegurar un desarrollo de calidad, promoviendo el trabajo en equipo y el aprendizaje colaborativo.
