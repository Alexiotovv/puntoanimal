<script>
  let btn_salir=document.querySelector(".btn-exit-system");
  btn_salir.addEventListener('click', function (e){
    e.preventDefault();
    Swal.fire({
      title: '¿Quieres salir del sitema?',
      text: "La sesion actual se cerrara",
      type: 'question',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, salir',
      cancelButtonText: 'No, cancelar'
    }).then((result) => {
      if (result.value) {
        let url='<?php echo SERVERURL; ?>ajax/loginAjax.php';
        let token='<?php echo $ins_loginc->encryption($_SESSION['token_vetp']); ?>';
        let usuario='<?php echo $ins_loginc->encryption($_SESSION['usuario_vetp']); ?>';

        let datos = new FormData();
        datos.append("token", token);
        datos.append("usuario", usuario);

        $.ajax({
        url: url,
        type: "POST",
        data: datos,
        cache: false,
        contentType: false,
        processData: false,
        dataType:"json",
        success:function(data){
          // console.log("MI DATA: "+data);
            return alertas_ajax(data);
        }
      });
 
      }
    });

  });
</script>
