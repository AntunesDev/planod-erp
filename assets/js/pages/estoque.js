const newClientBtn = $("#newEstoqueBtn");
const tableCard = $("#tableCard");
const formCard = $("#formCard");
const returnBtn = $("#returnBtn");
const form = $("#form");
const produto = $("#produto");
const tipo_de_movimentacao = $("#tipo_de_movimentacao");
const quantidade_movimentada = $("#quantidade_movimentada");

$(document).ready(() => {
  formCard.hide();

  let columns = {
    columns: [
      { data: "produto" },
      { data: "quantidade" },
      {
        data: null,
        render: (data, type, row) => {
          if (data.ultima_movimentacao == null) {
            return `Nada ainda`;
          } else {
            return new Date(
              Date.parse(data.ultima_movimentacao)
            ).toLocaleString("en-GB");
          }
        },
      },
    ],
    aoColumnDefs: [{ bSortable: false, aTargets: [] }],
  };

  let dataTable = new DataTableClass("#dataTable", "Estoque/selectAll");
  dataTable.loadTable(columns);

  axios.post("Produtos/listAll").then(({ data }) => {
    data.forEach((prd) => {
      produto.append(
        $("<option></option>", {
          text: prd.descricao,
          value: prd.identificador,
        })
      );
    });
  });

  newClientBtn.click(() => {
    tableCard.hide();
    formCard.show();
  });

  returnBtn.click(() => {
    window.location.reload();
  });

  form.submit(() => {
    let formData = new FormData(form[0]);

    axios.post("HistoricoEstoque/create", formData).then(({ data }) => {
      if (data.success == false) {
        Swal.fire(
          "Oops...",
          "Ocorreu um erro desconhecido ao tentar salvar a movimentação do cliente.",
          "error"
        );
      } else {
        window.location.reload();
      }
    });
  });
});
