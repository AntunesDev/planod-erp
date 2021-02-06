const newClientBtn = $("#newClientBtn");
const tableCard = $("#tableCard");
const formCard = $("#formCard");
const returnBtn = $("#returnBtn");
const form = $("#form");
const nome = $("#nome");
const telefone = $("#telefone");
const email = $("#email");

$(document).ready(() => {
  formCard.hide();

  let columns = {
    columns: [
      { data: "identificador" },
      { data: "nome" },
      { data: "telefone" },
      { data: "email" },
      {
        data: null,
        render: (data, type, row) => {
          return `<center>
            <button type="button" class="btn btn-outline-warning mb-1 btnEditar" identificador=${data.identificador}>Editar</button>
          </center>`;
        },
      },
    ],
    aoColumnDefs: [{ bSortable: false, aTargets: [4] }],
  };

  let dataTable = new DataTableClass("#dataTable", "Clientes/selectAll");
  dataTable.loadTable(columns);

  newClientBtn.click(() => {
    tableCard.hide();
    formCard.show();
  });

  telefone.inputmask({
    mask: ["(99) 9999-9999", "(99) 99999-9999"],
    keepStatic: true,
  });

  returnBtn.click(() => {
    window.location.reload();
  });

  form.submit(() => {
    let formData = new FormData(form[0]);

    if (form.find("#identificador").length > 0) {
      endpoint = "Clientes/edit";
    } else {
      endpoint = "Clientes/create";
    }

    axios.post(endpoint, formData).then(({ data }) => {
      if (data.success == false) {
        Swal.fire(
          "Oops...",
          "Ocorreu um erro desconhecido ao tentar salvar o cliente.",
          "error"
        );
      } else {
        window.location.reload();
      }
    });
  });

  $(document).on("click", ".btnEditar", (event) => {
    identificador = $(event.currentTarget).attr("identificador");
    let formData = new FormData();
    formData.append("identificador", identificador);
    axios.post("Clientes/selectById", formData).then(({ data }) => {
      if (data.success) {
        form.append(
          $("<input>", {
            type: "hidden",
            id: "identificador",
            name: "identificador",
            value: `${data.results[0].identificador}`,
          })
        );
        email.val(data.results[0].email);
        nome.val(data.results[0].nome);
        telefone.val(data.results[0].telefone);
        tableCard.hide();
        formCard.show();
      }
    });
  });

  $(document).on("click", ".btnExcluir", (event) => {
    identificador = $(event.currentTarget).attr("identificador");

    Swal.fire({
      title: "Excluir o cliente selecionado?",
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: `Excluir`,
      denyButtonText: `Cancelar`,
      icon: "question",
    }).then((result) => {
      if (result.isConfirmed) {
        let formData = new FormData();
        formData.append("identificador", identificador);
        axios.post("Clientes/delete", formData).then(({ data }) => {
          if (data.success == true) {
            window.location.reload();
          } else {
            Swal.fire(
              "Oops...",
              "Ocorreu um erro ao concluir a operação!",
              "error"
            );
          }
        });
      }
    });
  });
});
