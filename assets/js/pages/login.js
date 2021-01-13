const inputLogin = $("#inputLogin");
const inputPassword = $("#inputPassword");
const btnLogin = $("#btnLogin");

$(document).ready(() => {
  inputPassword.keypress((event) => {
    keyCode = event.keyCode ? event.keyCode : event.which;
    if (keyCode == "13") {
      btnLogin.click();
    }
  });

  btnLogin.click(() => {
    formData = new FormData();
    formData.append("login", inputLogin.val());
    formData.append("password", inputPassword.val());

    axios.post("Login/login", formData).then(({ data }) => {
      if (data.success == false) {
        swal.fire("Oops...", data.message, "error");
      } else {
        window.location.reload();
      }
    });
  });
});

$(document).arrive(".eapps-link", () => {
  $(".eapps-link").hide();
  $(document).unbindArrive(".eapps-link");
});
