<?php

 class vistasModelo{

    /*  Recive nombre de url, verifica se existe el archivo 
    *   @param: ruta desde url de mi vista: array $ruta[0]
    *   @return:variable $contenido, el archivo php encontrado en la carpeta vistas/contenido o login,404
    */
    protected static function obtener_vistas_modelo($vistas){
       // lista url: palabras permitidas en la url 
       $listaUrl = ["addUsuario","listaUsuario","editUsuario","editCuenta","addCitaM","addCliente","addHistorialM","addMascota","addNuevaVenta","addProdservi","datosEmpresa","editCita","editCliente","editMascota","editProdservi","home","listaCita","listaCitaHoy","listaCliente","listaEspecie","listaMascota","listaProdservi","listaRaza","listaVenta","nuevaVenta","perfilCliente","perfilMascota","estadisticas","listaVacuna","listaVeterinario","addVeterinario","editVeterinario","perfilVeterinario"];

       // si el valor de $vista se encuentra en la lista es true
       if (in_array($vistas,$listaUrl)) {
           // si el valor de vista existe en la carpeta contenido
           if (is_file("./vistas/contenidos/".$vistas."-view.php")) {
               $contenido="./vistas/contenidos/".$vistas."-view.php";
               
           } else {
               // no existe archivo o a sido eliminado
               $contenido="404";
           }
           
         }elseif($vistas=="login" || $vistas=="index"){
           $contenido="login";
         }else{
             $contenido="404";
          }

          return $contenido;
       
    }

}