const mysql = require('mysql2/promise');
const connection = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'proyectoredes'
});
// traer productos
async function traerProductos() {
    const result = await connection.query('SELECT * FROM productos'); 
    return result[0];
}
//traer un producto en especifico 
async function traerProducto(id) {
    const result = await connection.query('SELECT * FROM productos WHERE ID = ?', id);
    return result[0];
}
//actualizar un producto inventario e id
async function actualizarProducto(id, inventario) {
    const result = await connection.query('UPDATE productos SET Inventario = ? WHERE id = ?', [inventario, id]);
    return result;
}
//crear producto 
async function crearProducto(codigo, nombre, precio, inventario) {
    const result = await connection.query('INSERT INTO productos VALUES(null,?,?,?,?)', [codigo, nombre, precio, inventario]);
    return result;
}
// Eliminar un producto por su ID
async function eliminarProducto(id) {
    const result = await connection.query('DELETE FROM productos WHERE ID = ?', id);
    return result[0].affectedRows > 0;
}
module.exports = {
    traerProductos, traerProducto, actualizarProducto, crearProducto, eliminarProducto
};
