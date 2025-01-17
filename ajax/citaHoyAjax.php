<?php

$peticionAjax = true;
require_once "../config/APP.php";

require_once "../controladores/citaControlador.php";
require_once "../controladores/vacunaControlador.php";
require_once "../controladores/inventarioControlador.php";
$ins_cita = new citaControlador();
$ins_vacuna = new vacunaControlador();
$ins_prodVencido = new inventarioControlador();

/*-------- Listrar citas hoy ----------------*/
if (isset($_POST['fecha_hoy'])) {
    $data = $ins_cita->citas_hoy_controlador();
    echo $data;
}

/*-------- Listrar vacunas proximas ----------------*/
if (isset($_POST['prox_vacuna'])) {
    $data = $ins_vacuna->vacuna_proxima_controlador();
    echo $data;
}

/*-------- Listrar vacunas proximas ----------------*/
if (isset($_POST['prod_vencido'])) {
    $data = $ins_prodVencido->producto_vencido_controlador();
    echo $data;
}