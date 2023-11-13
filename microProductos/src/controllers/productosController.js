const { Router } = require('express');
const router = Router();
const productosModel = require('../models/productosModel');

//mostrar productos
router.get('/productos', async (req, res) => {
    const id = req.params.id;
    var result;
    result = await productosModel.traerProductos() ;
    res.json(result);
});
//traer un producto por su id 
router.get('/productos/:id', async (req, res) => {
    const id = req.params.id;
    var result;
    result = await productosModel.traerProducto(id) ;
    res.json(result[0]);
});
//actualizar un producto
router.put('/productos/:id', async (req, res) => {
    const id = req.params.id;
    const inventario = req.body.inventario;
    if (inventario<0) {
        res.send("el inventario no puede ser menor de cero");
        return;
    }
    var result = await productosModel.actualizarProducto(id, inventario);
    res.send("inventario de producto actualizado");
});
// Crear un producto 
router.post('/productos', async (req, res) => {
    const codigo = req.body.codigo;
    const nombre = req.body.nombre;
    const precio = req.body.precio;
    const inventario = req.body.inventario


    // Validar que los campos requeridos no estén vacíos
    if (!nombre || !precio || inventario === undefined) {
        res.status(400).send("Todos los campos son requeridos.");
        return;
    }

    var result = await productosModel.crearProducto(codigo, nombre, precio, inventario);
    res.send("Producto creado");
});
// Eliminar un producto
router.delete('/productos/:id', async (req, res) => {
    const id = req.params.id;
    const result = await productosModel.eliminarProducto(id);
    if (result) {
        res.send("Producto eliminado");
    } else {
        res.status(404).send("Producto no encontrado");
    }
});

module.exports = router;