<?php
    
     $peticionAjax=true;
     require_once "../config/APP.php";
     
     if(isset($_POST['empresa_rif_reg']) || isset($_POST['id_empresa_up'])){
        require_once "../controladores/empresaControlador.php";
        $insEm=new empresaControlador();

        /*-------- Egregar empresa ----------------*/
        if(isset($_POST['empresa_rif_reg']) && isset($_POST['empresa_nombre_reg'])){
            echo $insEm->agregar_empresa_controlador();
        }
        /*-------- Actualizar empresa ----------------*/
        if(isset($_POST['id_empresa_up']) && isset($_POST['empresa_rif_edit']) ){
            echo $insEm->actualizar_empresa_controlador();
        }

     }else{
        // si no esta definido
        session_start(['name'=>'VETP']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }