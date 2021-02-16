const newEstoqueBtn = $("#newEstoqueBtn");
const tableCard = $("#tableCard");
const formCard = $("#formCard");
const returnBtn = $("#returnBtn");
const form = $("#form");
const produto = $("#produto");
const tipo_de_movimentacao = $("#tipo_de_movimentacao");
const quantidade_movimentada = $("#quantidade_movimentada");

$(document).ready(() => {
  produto.select2({
    placeholder: 'Selecione uma opção',
    ajax: {
      url: "Produtos/searchByText/",
      datatype: 'json',
      data: (params) => {
        var query = {
          'searchText': params.term
        }
        return query;
      },
      processResults: (data) => {
        var data = JSON.parse(data);
        return {
          results: data.results
        }
      }
    }
  });
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
      {
        data: null,
        render: (data, type, row) => {
          return `<center>
            <button type="button" class="btn btn-warning mb-1 btnNovaMovimentacao" identificador=${data.identificador} produto="${data.produto}">Nova Movimentação</button>
          </center>`;
        },
      },
    ],
    aoColumnDefs: [{ bSortable: false, aTargets: [3] }],
  };

  let dataTable = new DataTableClass("#dataTable", "Estoque/selectAll");
  dataTable.loadTable(columns);

  newEstoqueBtn.click(() => {
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

  $(document).on("click", ".btnNovaMovimentacao", (event) => {
    let currentTarget = $(event.currentTarget);
    produto.append(new Option(currentTarget.attr("produto"), currentTarget.attr("identificador"), false, true)).trigger('change');
    tableCard.hide();
    formCard.show();
  })
});
