const { Router } = require('express');
const router = Router();
const usuariosModel = require('../models/usuariosModel');
//traer usuarios
router.get('/usuarios', async (req, res) => {
    var result;
    result = await usuariosModel.traerUsuarios() ;
    res.json(result);
});
//traer un usuario en especifico 
router.get('/usuarios/:usuario', async (req, res) => {
    const usuario = req.params.usuario;
    var result;
    result = await usuariosModel.traerUsuario(usuario) ;
    res.json(result[0]);
});
//validar credenciales de usuario 
router.get('/usuarios/:usuario/:password', async (req, res) => {
    const usuario = req.params.usuario;
    const password = req.params.password;
    var result;
    result = await usuariosModel.validarUsuario(usuario, password) ;
    res.json(result);
});
//crear un usuario 
router.post('/usuarios', async (req, res) => {
    const nombre = req.body.nombre;
    const usuario = req.body.usuario;
    const password = req.body.password;
    const rol = req.body.rol; // Agregamos el campo "rol"
    
    var result = await usuariosModel.crearUsuario(nombre, usuario, password, rol);
    res.send("Usuario creado");
});
// Ruta para eliminar un usuario por su nombre de usuario
router.delete('/usuarios/:usuario', async (req, res) => {
    const usuario = req.params.usuario;
    const result = await usuariosModel.eliminarUsuario(usuario);
    res.send("Usuario eliminado");
});

// Ruta para modificar un usuario por su nombre de usuario
router.put('/usuarios/:usuario', async (req, res) => {
    const usuario = req.params.usuario;
    const updatedUser = req.body;
    const result = await usuariosModel.modificarUsuario(usuario, updatedUser);
    res.send("Usuario modificado");
});

module.exports = router;