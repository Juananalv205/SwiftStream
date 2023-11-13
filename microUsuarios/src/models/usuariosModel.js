const mysql = require('mysql2/promise');
const connection = mysql.createPool({
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'proyectoredes'
});
//traer Usuarios
async function traerUsuarios() {
    const result = await connection.query('SELECT * FROM usuarios');
    return result[0];
}
//traer Usuario por su nombre de usuario 
async function traerUsuario(usuario) {
    const result = await connection.query('SELECT * FROM usuarios WHERE Usuario = ?', usuario);
    return result[0];
}
//validar Usuario
async function validarUsuario(usuario, password) {
    const result = await connection.query('SELECT * FROM usuarios WHERE Usuario = ? AND Contraseña = ?', [usuario, password]);
    const user = result[0][0];
    
    if (user) {
        const { Rol } = user;
        if (Rol === "Clientes") {
            // Autenticar como cliente
            return "Clientes";
        } else if (Rol === "Vendedor") {
            // Autenticar como administrador
            return "Vendedor";
        } else {
            return "Rol no reconocido";
        }
    } else {
        return "Usuario o contraseña incorrectos";
    }
}
//crear Usuario
async function crearUsuario(nombre,usuario, password, rol) {
    const result = await connection.query('INSERT INTO usuarios (Nombre, Usuario, Contraseña, Rol) VALUES (?,?,?,?)', [nombre,usuario, password, rol]);
    return result;
}
// Eliminar un usuario por su nombre de usuario
async function eliminarUsuario(usuario) {
    const result = await connection.query('DELETE FROM usuarios WHERE Usuario = ?', usuario);
    return result;
}
// Modificar un usuario por su nombre de usuario
async function modificarUsuario(usuario, updatedUser) {
    const result = await connection.query('UPDATE usuarios SET ? WHERE Usuario = ?', [updatedUser, usuario]);
    return result;
}
    module.exports = {
    traerUsuarios, traerUsuario, validarUsuario, crearUsuario, eliminarUsuario, modificarUsuario
};