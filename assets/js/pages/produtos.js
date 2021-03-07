const newProdutoBtn = $("#newProdutoBtn");
const printTabelaBtn = $("#printTabelaBtn");
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

  let columnsImagens = {
    columns: [
      {
        data: null,
        render: (data, type, row) => {
          return `<center>
            <img alt="${data.imagem}" src="${BASE_URL}/views/images/${data.imagem}" class="square-thumb">
          </center>`;
        },
      },
      {
        data: null,
        render: (data, type, row) => {
          return `<center>
            <button type="button" class="btn btn-outline-danger mb-1 btnExcluirImagem" identificador=${data.identificador} nome="${data.imagem}">Excluir</button>
          </center>`;
        },
      },
    ],
    aoColumnDefs: [{ bSortable: false, aTargets: [0, 1] }],
  };

  let dataTable = new DataTableClass("#dataTable", "Produtos/selectAll");
  dataTable.loadTable(columns);

  newProdutoBtn.click(() => {
    tableCard.hide();
    formCard.show();
  });

  printTabelaBtn.click(() => {
    axios.post("Relatorios/catalogoSemImagens", null, { responseType: "arraybuffer" }).then(response => {
      if (response.data.byteLength != 0) {
        let blob = new Blob([response.data], { type: "application/pdf" });
        let link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.target = "_blank";
        link.click();
      } else {
        Swal.fire(
          "Oops...",
          "Não há informações suficientes para emitir o relatório selecionado.",
          "error"
        );
      }
    });
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
      if (data.success == false && typeof data.message !== "undefined") {
        Swal.fire("Oops...", data.message, "error");
      } else if (data.success == false) {
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
            value: `${data.results[0].identificador}`,
          })
        );
        descricao.val(data.results[0].descricao);
        preco_de_venda.val(data.results[0].preco_de_venda.replace(".", ","));
        preco_de_compra.val(data.results[0].preco_de_compra.replace(".", ","));
        $.applyDataMask("#preco_de_venda, #preco_de_compra");
        tableCard.hide();
        formCard.show();

        let dataTableImagens = new DataTableClass(
          "#dataTableImagens",
          "Produtos/selectAllImages",
          { produto: identificador }
        );
        dataTableImagens.loadTable(columnsImagens);
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

  $(document).on("click", ".btnExcluirImagem", (event) => {
    identificador = $(event.currentTarget).attr("identificador");
    nome = $(event.currentTarget).attr("nome");

    Swal.fire({
      title: "Excluir a imagem selecionada?",
      showDenyButton: true,
      showCancelButton: false,
      confirmButtonText: `Excluir`,
      denyButtonText: `Cancelar`,
      icon: "question",
    }).then((result) => {
      if (result.isConfirmed) {
        let formData = new FormData();
        formData.append("identificador", identificador);
        formData.append("nome", nome);
        axios.post("Produtos/deleteImagem", formData).then(({ data }) => {
          if (data.success == true) {
            $("#dataTableImagens").DataTable().ajax.reload();
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

$(document).on("change", 'input[type="file"]', (event) => {
  currentTarget = $(event.currentTarget);
  label = $(currentTarget.parent().children("label"));

  var fullPath = currentTarget.val();
  if (fullPath) {
    var startIndex =
      fullPath.indexOf("\\") >= 0
        ? fullPath.lastIndexOf("\\")
        : fullPath.lastIndexOf("/");
    var filename = fullPath.substring(startIndex);
    if (filename.indexOf("\\") === 0 || filename.indexOf("/") === 0) {
      filename = filename.substring(1);
    }

    if (filename.endsWith("jpeg") || filename.endsWith("jpg")) {
      if (form.find("#identificador").length > 0) {
        let formData = new FormData();
        formData.append("imagem", currentTarget[0].files[0]);
        formData.append("produto", form.find("#identificador").val());
        axios
          .post("Produtos/addImage", formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          .then(({ data }) => {
            if (data.success == false) {
              Swal.fire("Oops...", data.message, "error");
            } else {
              $("#dataTableImagens").DataTable().ajax.reload();
            }
          });
      } else {
        label.text(filename);
        label.addClass("disabled");
      }
    } else {
      Swal.fire("Oops...", "Este não é um arquivo de imagem válido!", "error");
      currentTarget.val("");
    }
  }
});
