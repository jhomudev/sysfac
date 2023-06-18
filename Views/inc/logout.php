<script>
  const btnlogout = document.querySelectorAll('.btnLogout');

  btnlogout.forEach(btn => {
    btn.addEventListener('click', () => {
      Swal.fire({
        title: "Estas seguro de abandonar la sesión?",
        text: "La sesión se cerrará y saldrá del sistema",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Aceptar",
        cancelButtonText: "Cancelar",
      }).then(async (result) => {
        try {
          if (result.isConfirmed) {
            const req = await axios.post("<?php echo SERVER_URL ?>/Request/loginRequest.php",
              new URLSearchParams(`token=<?php echo $lc->encryption($_SESSION['token']); ?>`)
            );
            const res = await req.data;
            alertRequest(res);
          }
        } catch (error) {
          console.log(error);
        }
      });
    })
  });
</script>