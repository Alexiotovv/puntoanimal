<?php
    
    $peticionAjax=true;
    require_once "../config/APP.php";
     
     if(isset($_POST['nota_descripcion_reg']) || isset($_POST['limit'])  || isset($_POST['nota_id_dele']) || isset($_POST['nota_id_edit']) || isset($_POST['nota_id_up']) ){
        require_once "../controladores/notasControlador.php";
        $insNota=new notasControlador();
        /*--- agregar nota ----*/
        if(isset($_POST['nota_codmascota_reg']) && isset($_POST['nota_descripcion_reg'])){
            echo $insNota->agregar_notas_controlador();
        }
        /*---- Mostrar notas guardadas*/
        if(isset($_POST['limit']) && isset($_POST['offset'])){
            echo $insNota->mostrar_notas_controlador();
        }

        /*-------- Eliminar una nota ----------------*/
        if(isset($_POST['nota_id_dele'])   ){
            echo $insNota->eliminar_nota_controlador();
        }
        /*-------- Buscar nota a editar mostar en modal ----------------*/
        if(isset($_POST['nota_id_edit'])){
            $data = $insNota->datos_nota_controlador("Unico",$_POST['nota_id_edit']);
            $data = $data->fetch();
            
            $jsonstring = json_encode($data);
            echo $jsonstring;
             
        }
        /*-------- Actualizar nota ----------------*/
        if(isset($_POST['nota_id_up']) && isset($_POST['nota_codmascota_up']) && isset($_POST['nota_descripcion_up']) ){
            echo $insNota->actualizar_nota_controlador();
        }

     }else{
        // si no esta definido
        session_start(['name'=>'VETP']);
        session_unset();
        session_destroy();
        header("Location: ".SERVERURL."login/");
        exit();
    }