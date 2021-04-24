# Inventario
> Inventario en Symfony 5

Preview
<img src="https://github.com/HTMyName/inventario/blob/main/preview.png">

## Requerimientos
1. Composer
2. PHP > 7.0

## Instalación
1. Instalar las dependencias del proyecto
```sh
composer install
```

2. Crear una base de datos en MYSQL llamada inventario
```sql
CREATE DATABASE inventario 
```

3. Generar la Base de Datos MySql
```sh
php bin/console doctrine:schema:update --force
``` 

4. Ejecutar Proyecto
```sh
php bin/console server:run
``` 

5. Ingresar a la url:
> *localhost:8000* [Entrar](http://localhost:8000)
