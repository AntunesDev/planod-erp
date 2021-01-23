$(document).ready(() => {
  let columns = {
    columns: [
      { data: "produto" },
      { data: "tipo_de_movimentacao" },
      { data: "quantidade_antes" },
      {
        data: null,
        render: (data, type, row) => {
          if (data.tipo_de_movimentacao == "Entrada") {
            return `<div class="alert alert-primary text-center" role="alert">
            + ${data.quantidade_movimentada}
            </div>`;
          } else {
            return `<div class="alert alert-danger text-center" role="alert">
            - ${data.quantidade_movimentada}
            </div>`;
          }
        },
      },
      { data: "quantidade_depois" },
      {
        data: null,
        render: (data, type, row) => {
          return new Date(Date.parse(data.momento)).toLocaleString("en-GB");
        },
      },
    ],
    aoColumnDefs: [{ bSortable: false, aTargets: [] }],
    order: [[5, "desc"]],
  };

  let dataTable = new DataTableClass(
    "#dataTable",
    "HistoricoEstoque/selectAll"
  );
  dataTable.loadTable(columns);
});
