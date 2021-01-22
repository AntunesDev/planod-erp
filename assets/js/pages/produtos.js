const newProdutoBtn = $("#newProdutoBtn");
const tableCard = $("#tableCard");
const formCard = $("#formCard");
const returnBtn = $("#returnBtn");
const form = $("#form");
const descricao = $("#descricao");
const preco_de_venda = $("#preco_de_venda");
const preco_de_compra = $("#preco_de_compra");

$(document).ready(() => {
  formCard.hide();

  let columns = {
    columns: [
      { data: "identificador" },
      { data: "descricao" },
      {
        data: null,
        render: (data, type, row) => {
          return `R$ ${Number(data.preco_de_venda).toFixed(2)}`;
        },
      },
      {
        data: null,
        render: (data, type, row) => {
          return `R$ ${Number(data.preco_de_compra).toFixed(2)}`;
        },
      },
      {
        data: null,
        render: (data, type, row) => {
          if (data.preco_de_compra == 0) {
            lucro = 100;
          } else {
            lucro =
              ((data.preco_de_venda - data.preco_de_compra) * 100) /
              data.preco_de_compra;
          }
          return `${lucro.toFixed(2)} %`;
        },
      },
      {
        data: null,
        render: (data, type, row) => {
          return `<center>
            <button type="button" class="btn btn-outline-warning mb-1 btnEditar" identificador=${data.identificador}>Editar</button>
            <button type="button" class="btn btn-outline-danger mb-1 btnExcluir" identificador=${data.identificador}>Excluir</button>
          </center>`;
        },
      },
    ],
    aoColumnDefs: [{ bSortable: false, aTargets: [5] }],
  };

  let dataTable = new DataTableClass("#dataTable", "Produtos/selectAll");
  dataTable.loadTable(columns);

  newProdutoBtn.click(() => {
    tableCard.hide();
    formCard.show();
  });

  preco_de_venda.mask("#.##0,00", { reverse: true });
  preco_de_compra.mask("#.##0,00", { reverse: true });

  returnBtn.click(() => {
    window.location.reload();
  });

  form.submit(() => {
    let formData = new FormData(form[0]);

    if (form.find("#identificador").length > 0) {
      endpoint = "Produtos/edit";
    } else {
      endpoint = "Produtos/create";
    }

    axios.post(endpoint, formData).then(({ data }) => {
      if (data.success == false) {
        Swal.fire(
          "Oops...",
          "Ocorreu um erro desconhecido ao tentar salvar o produto.",
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
    axios.post("Produtos/selectById", formData).then(({ data }) => {
      if (data.success) {
        form.append(
          $("<input>", {
            type: "hidden",
            id: "identificador",
            name: "identificador",
            value: `${data.results.identificador}`,
          })
        );
        descricao.val(data.results.descricao);
        preco_de_venda.val(data.results.preco_de_venda);
        preco_de_compra.val(data.results.preco_de_compra);
        $.applyDataMask("#preco_de_venda, #preco_de_compra");
        tableCard.hide();
        formCard.show();
      }
    });
  });

  $(document).on("click", ".btnExcluir", (event) => {
    identificador = $(event.currentTarget).attr("identificador");

    Swal.fire({
      title: "Excluir o produto selecionado?",
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: `Excluir`,
      denyButtonText: `Cancelar`,
      icon: "question",
    }).then((result) => {
      if (result.isConfirmed) {
        let formData = new FormData();
        formData.append("identificador", identificador);
        axios.post("Produtos/delete", formData).then(({ data }) => {
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
