const express = require('express');
const router = express.Router(); 
const axios = require('axios');
const ventasModel = require('../models/ventasModel');

//traer ventas 
router.get('/ventas/', async (req, res) => {
    var result;
    result = await ventasModel.traerVentas() ;
    res.json(result);
});

//traer ventas por su id 
router.get('/ventas/:id', async (req, res) => {
    const id = req.params.id;
    var result;
    result = await ventasModel.traerVenta(id) ;
    res.json(result[0]);
});

// Buscar ventas por nombre de cliente
router.get('/ventas/nombre/:nombreCliente', async (req, res) => {
    const nombreCliente = req.params.nombreCliente;
    var result;
    result = await ventasModel.buscarVentasPorNombre(nombreCliente);
    res.json(result);
});

//crear una venta 
router.post('/ventas', async (req, res) => {
    //Extrae de la solicitud 
    const usuario = req.body.usuario;
    const items = req.body.items;
    const disponibilidad = await ventasModel.verificarDisponibilidad(items);

    //Verifica si hay cantidad en el inventario de productos
    if (!disponibilidad) {
        return res.json({ error: 'No hay disponibilidad de productos' });
    }
    
    //Verifica que el precio sea menor o igual a 0 y notifica error
    const ventaTotal = await ventasModel.calcularTotal(items);

    if (ventaTotal <= 0) {
        return res.json({ error: 'Cantidad de productos invalida' });
    }

    // SE conecta al microservicio de usuarios para estraer informacio
    const responseUsuario = await axios.get(`http://localhost:3001/usuarios/${usuario}`);
    const nombreCliente = responseUsuario.data.Nombre;

    const productosVendidos = [];
    const codigo = [];
    const cantidad = [];
    // Se concecta al microservicio de productos
    for (const producto of items) {
        const cantidadproductos = producto.cantidad
        const responseProducto = await axios.get(`http://localhost:3002/productos/${producto.id}`);
        const nombreProducto = responseProducto.data.Nombre;

        const codigoproducto = responseProducto.data.Codigo;

        productosVendidos.push(nombreProducto);
        codigo.push(codigoproducto);
        cantidad.push(cantidadproductos);
    }
    
    const venta = {
        Nombre : nombreCliente,
        Codigo : codigo,
        Producto : productosVendidos,
        Cantidad : cantidad,
        Totalventas : ventaTotal
    };

    const ventasRes = await ventasModel.crearVenta(venta);
    await ventasModel.actualizarInventario(items);

    return res.send("Venta creada");
});



// Borrar una venta por su ID
router.delete('/ventas/:id', async (req, res) => {
    const id = req.params.id;
    await ventasModel.borrarVenta(id);
    res.send('Venta eliminada');
});

module.exports = router;


