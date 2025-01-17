<?php

const SERVER="localhost";
const BD="puntoanimal";
const USER="root";
const PASS="";


const SGBD="mysql:host=".SERVER.";dbname=".BD;

// METODOS ENCRIPTAR : no cambiar una vez haya registro en la BD
// NO SE PUEDE MODIFICAR METHOD
// SECRET_KEY: llave secreta se puede modificar
// SECRET_IV: numero unico, colocar cualquier numero
const METHOD="AES-256-CBC";  
const SECRET_KEY='$VT@2020';
const SECRET_IV='881992';
