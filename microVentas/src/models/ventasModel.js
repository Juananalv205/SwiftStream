const mysql = require('mysql2/promise');
const express = require('express');
const router = express.Router(); 
const axios = require('axios');
const connection = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'proyectoredes'
});

//crearventa
async function crearVenta(venta) {
    const nombre = venta.Nombre;
    const codigos = JSON.stringify(venta.Codigo); // Convertimos el arreglo de códigos a una cadena JSON
    const productos = JSON.stringify(venta.Producto); // No necesitamos JSON.stringify aquí
    const cantidades = JSON.stringify(venta.Cantidad); // Convertimos el arreglo de cantidades a una cadena JSON
    const totalVentas = venta.Totalventas;


    const result = await connection.query('INSERT INTO ventas VALUES (null, NOW(), ?, ?, ?, ?, ?)', [nombre, codigos, productos, cantidades, totalVentas]);
    return result;
}


//traer venta por su id 
async function traerVenta(id) {
    const result = await connection.query('SELECT * FROM ventas WHERE ID =  ?', id);
    return result[0];
}
//traer todas las ventas 
async function traerVentas() {
    const result = await connection.query('SELECT * FROM ventas');
    return result[0];
}
// Buscar ventas por nombre de cliente
async function buscarVentasPorNombre(nombreCliente) {
    const result = await connection.query('SELECT * FROM ventas WHERE Nombre = ?', nombreCliente);
    return result[0];
}
// Eliminar una venta por su ID
async function borrarVenta(id) {
    const result = await connection.query('DELETE FROM ventas WHERE ID = ?', id);
    return result[0];
}

// Función para calcular el total de la venta
async function calcularTotal(items) {
    let ventaTotal = 0;
    for (const producto of items) {
        const response = await axios.get(`http://localhost:3002/productos/${producto.id}`);
        ventaTotal += response.data.Precio * producto.cantidad;
    }
    return ventaTotal;
}

// Función para verificar si hay suficientes unidades de los productos para realizar la venta
async function verificarDisponibilidad(items) {
    let disponibilidad = true;
    for (const producto of items) {
        const response = await axios.get(`http://localhost:3002/productos/${producto.id}`);
        if (response.data.Inventario < producto.cantidad) {
            disponibilidad = false;
            break;
        }
    }
    return disponibilidad;
}

// Función para disminuir la cantidad de unidades de los productos
async function actualizarInventario(items) {
    for (const producto of items) {
        const response = await axios.get(`http://localhost:3002/productos/${producto.id}`);
        const inventarioActual = response.data.Inventario;
        const inv = inventarioActual - producto.cantidad;
        await axios.put(`http://localhost:3002/productos/${producto.id}`, {
            inventario: inv
        });
    }
}

module.exports = {
    crearVenta,
    traerVenta,
    traerVentas,
    borrarVenta,
    buscarVentasPorNombre,
    calcularTotal,
    verificarDisponibilidad,
    actualizarInventario
};