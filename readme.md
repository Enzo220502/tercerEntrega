# Productos de Belleza Api

_Consumiendo esta API podran ver el nombre, descripcion, precio, marca,imagen y id de la categoria a la que pertenece cada uno de nuestros diferentes productos de belleza_

## ENDPOINTS

* localhost/tpe-api/api/productos/ (GET)
* localhost/tpe-api/api/productos/ (POST)
* localhost/tpe-api/api/productos/:ID (GET ID)
* localhost/tpe-api/api/productos/:ID (PUT)
* localhost/tpe-api/api/productos/:ID (DELETE)
* localhost/tpe-api/api/auth/token (GET AUTORIZACION)

## Servicios GET
### OBTENER TODOS LOS PRODUCTOS
 _Para poder acceder a todos los registros de la BBDD utilizamos el metod GET._
```
localhost/tpe-api/api/productos/
```
### OBTENER UN PRODUCTO POR ID
_Para poder acceder a un registro de la BBDD por ID tambien utilizamos el metodo GET._
* localhost/tpe-api/api/productos/:ID
```
localhost/tpe-api/api/productos/**19**
```
### ordenPor & orden
_utilizando los query params ordenPor & orden podemos establecer un orden ascendente 'asc' o descentendete 'desc' a una clasificacion ingresada en 'ordenPor'_
ordenPor:
* ID (int 11)
* nombre (varchar 45)
* descripcion (varchar 45)
* precio (int11)
* marca (varchar 45)
* id_categoria (int 11)
* imagen (varchar 255) (ruta en la cual esta localizada esta misma)
orden:
* asc
* desc
```
localhost/tpe-api/api/productos?ordenPor=nombre&orden=asc
localhost/tpe-api/api/productos?ordenPor=precio&orden=desc
```

### FILTRO
_utilizando los query params CAMPO & VALOR podemos establecer el valor de una celda para poder filtrar. campo(columna) y valor(valor de la celda)_ - ej: campo=nombre & valor=Lima
       
* nombre (varchar 45) 
```
localhost/tpe-api/api/productos?campo=nombre&valor=Shampoo
```
* descripcion (varchar 45)
```
localhost/tpe-api/api/productos?campo=descripcion&valor=Color+Nude+Y+Larga+Duracion
```
* precio (int11) 
```
localhost/tpe-api/api/productos?campo=precio&valor=2500
```
* marca (varchar 45) 
```
localhost/tpe-api/api/productos?campo=marca&valor=Nivea
```
* id_categoria (int 11) 
```
localhost/tpe-api/api/productos?campo=id_categoria&valor=35
```
### PAGINACIÓN
_Para utilizar la paginacion debemos ingresar dos valroes para nuestras keys pagina y limite (registros que queremos mostrar). Podemos utilizar solo la key PAGINA y por defecto tendra un limite de 3 registros_   
```
localhost/tpe_api/api/productos?pagina=1
localhost/tpe_api/api/productos?pagina=1&limite=5    
```
### Conclusion filtro/orden/paginacion
* _Podemos utilizar solo campo&valor (en caso de que solo queramos buscar ese dato de esa columna)_
* _Podemos utilizar solo ordenPor&orden (en caso de q solo queramos cambiar el orden de lo q vemos en base a la columna q decidamos -ordenPor-)_
* _Podemos utilizar solo pagina o pagina&limite (en caso de que queramos paginar lo que ya estmos viendo en el get all)_
* _Podemos combinar estos query params como sea siempre y cuando esten los pares juntos salvo pagina que puede ir solo (campo&valor | ordenPor&orden | pagina&limite)_
```
localhost/tpe-api/api/productos?ordenPor=id&orden=desc&pagina=1&limite=5
(de esta forma, veremos todos los registros organizados por id descendente con un limite de 5 registros por pagina)
```
## Servicio POST (requiere autorizacion)
_Para insertar un registro en la BBDD necesitamos poner nuestro endpoint con el metodo POST (localhost/tpe-api/api/productos/)_
``` 
{
    "nombre": "Paleta de Colores",
    "descripcion": "Colores frios y neutros",
    "precio": 10000,
    "marca": "James Charles",
    "id_categoria": 35
}
```
_En caso de querer incluir una imagen,la misma debe estar subida en la web,agregando su correspondiente URL en el "body" del request, resultando de la siguiente manera_
```
{
  "nombre": "Paleta de Colores",
    "descripcion": "Colores frios y neutros",
    "precio": 10000,
    "marca": "James Charles",
    "id_categoria": 35,
    "imagen":"https://farmacityar.vtexassets.com/arquivos/ids/241821/220335_shampoo-pantene-miracle-fuerza-y-reconstruccion-x-400-ml___imagen-1.jpg?v=638188273621570000"
}
```
## Servicio PUT (requiere autorizacion)
_Para editar un registro en la BBDD necesitamos poner nuestro endpoint con el metodo PUT y saber el ID que vamos a editar (localhost/tpe-api/api/productos/:ID)_
  ```
localhost/tpe-api/api/productos/1
  ```  
_y luego debemos completar el siguiente json:_
```   
{
"nombre": "x", ------------>(varchar 45)           
"descripcion": "x", ------->(varchar 45)
"precio": x, -------------->(int 11)
"marca": "x", ------------->(varchar 45)
"id_categoria": x,--------->(int 11)
"imagen":"x"--------------->(varchar 255)
}
```
## Servicio DELETE (requiere autorizacion)
_para elimintar un registro en la BBDD debemos conocer el ID, utilizamos el endpoint con metodo DELETE (localhost/tpe-api/api/productos/:ID)_
```
localhost/tpe-api/api/productos/19 (delete)
```
# AUTORIZACION:
_para poder identificarnos en la api debemos utilizar el metodo GET y cambiar nuestro endpoint a:_
* localhost/tpe-api/auth/token 
(localhost/tpe-api/api/auth/token) 
_Luego con nuestro usuario y contraseña (Basic Auth postman) accedemos para poder recibir un token._
_Este token es el que nos da la autorizacion para poder insertar,editar o eliminar (Bearer Token)._
