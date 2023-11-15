"use stict"
addEventListener("DOMContentLoaded",obtenerProductos);
const URL = "http://186.136.81.128:80/api/productos/";
const URLC = "http://186.136.81.128:80/api/categorias/";

let localProds = [];
let localCats = [];

let form = document.querySelector('#add-form');
form.addEventListener('submit',agregarProducto);

async function obtenerProductos(){
    obtenerCategorias();
    let res = await fetch(URL);
    let productos = await res.json();
    for (const p of productos){
        localProds.push(p);
    }
    imprimirProductos();
}

function imprimirProductos(){
    let tbody = document.querySelector("#tbody");
    tbody.innerHTML = "";
    for (const prod of localProds) {
        tbody.innerHTML += `
        <tr>
            <td>${prod.nombre}</td>
            <td>${prod.precio}</td>
            <td>${prod.marca}</td>
            <td>${prod.nombre_categoria}</td>
            <td><div class = "d-flex justify-content-center"><a type = "button" href = "#" class="btn btn-primary btn-sm">Ver Mas</a></td>
            <td><div class = "d-flex justify-content-center"><a type = "button" href = "#" id = "editButton" class="btn btn-warning btn-sm">Editar</a></td>
            <td><div class = "d-flex justify-content-center"><a type = "button" data-id = "${prod.ID}" href = "#" id = "deleteButton" class="btn btn-danger btn-sm">Borrar</a></div></td>    
        </tr>`;
    }

    const btnsBorrar = document.querySelectorAll('a#deleteButton');
    for(const btn of btnsBorrar){
        btn.addEventListener('click',eliminarProducto);
    }

}

async function agregarProducto(e){
    e.preventDefault();

    let data = new FormData(form);
    let producto = {
        nombre : data.get('nombre'),
        descripcion : data.get('descripcion'),
        precio : data.get('precio'),
        marca : data.get('marca'),
        id_categoria : data.get('categoria'),
        imagen : null
    };
    
    try{
        let res = await fetch(URL, {
            method : "POST",
            headers : {'Content-Type' : 'application/json',
                      'Authorization' : 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJub21icmUiOiJ3ZWJhZG1pbiIsImlkIjoyfQ.FuEmPdkiCtPMbloQRASX0IwAou9JdBkYWZz-knSnvp0'},
            body : JSON.stringify(producto)
        });

        if(!res.ok){
            throw new Error("No se logro insertar el producto correctamente"); 
        }

        let nuevo = await res.json();

        localProds.push(nuevo);

        form.reset();

        imprimirProductos();

    }catch(e){
        console.log(e);
    }
}

async function eliminarProducto(e){
    e.preventDefault();
    try{
        let id = e.target.dataset.id;
        let res = await fetch(URL + id,{
            method : "DELETE",
            headers : {"Authorization" : "Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJub21icmUiOiJ3ZWJhZG1pbiIsImlkIjoyfQ.FuEmPdkiCtPMbloQRASX0IwAou9JdBkYWZz-knSnvp0"}
        });
        if(!res.ok){
            throw new Error('No ha sido posible eliminar la tarea con id = '+id);
        }

        localProds.filter(localProds => localProds.ID != id);

        imprimirProductos();

    }catch(e){
        console.log(e);
    }
}

async function obtenerCategorias(){
    let res = await fetch(URLC);
    let categorias = await res.json();
    localCats = categorias;
    imprimirCategorias(categorias);
}

function imprimirCategorias(categorias){
    let ubi = document.querySelector("#inputCategoria");
    for(const c of localCats){
        ubi.innerHTML += `<option value="${c.id}">${c.nombre_categoria}</option>`;
    }
}
