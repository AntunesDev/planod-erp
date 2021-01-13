<script src="assets/vendor/jquery/jquery.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="assets/vendor/chart.js/Chart.min.js"></script>
<script src="assets/vendor/arrive/arrive.min.js"></script>
<script src="assets/js/ruang-admin.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const btnUpdateSenha = $('#btnUpdateSenha');
    $(document).ready(() => {
        btnUpdateSenha.click(async () => {
            const {
                value: formValues
            } = await Swal.fire({
                title: 'Alterar senha',
                html: '<input id="swal-input1" class="swal2-input" placeholder="Insira sua nova senha" type="password">' +
                    '<input id="swal-input2" class="swal2-input" placeholder="Confirme sua nova senha" type="password">',
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('swal-input1').value,
                        document.getElementById('swal-input2').value
                    ]
                }
            })

            if (formValues) {
                password = formValues[0];
                passwordConfirmation = formValues[1];
                if (passwordConfirmation != password) {
                    Swal.fire("Oops...", "As senhas não coincidem.", "error");
                } else {
                    formData = new FormData();
                    formData.append("password", password)
                    axios.post("Login/updatePassword", formData).then(({
                        data
                    }) => {
                        if (data.success == true) {
                            Swal.fire("Sucesso!", "Senha alterada com sucesso!", "success");
                        } else {
                            Swal.fire("Oops...", "Ocorreu um erro ao alterar a senha...", "error");
                        }
                    })
                }
            }
        })
    })
</script>