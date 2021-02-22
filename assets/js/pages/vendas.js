const rowPagamento = $("#rowPagamento");
const rowVenda = $("#rowVenda");
const tableCarrinho = $("#tableCarrinho");
const form = $("#form");
const cliente = $("#cliente");
const valor_total = $("#valor_total");
const valor_desconto = $("#valor_desconto");
const valor_final = $("#valor_final");
const newVendaBtn = $("#newVendaBtn");
const toggleVendas = $("#toggleVendas");
const returnBtn = $("#returnBtn");

var exibeVendasAntigas = false;

const columnsVendas = {
  columns: [
    { data: "identificador" },
    { data: "cliente" },
    {
      data: null,
      render: (data, type, row) => {
        return new Date(Date.parse(data.data)).toLocaleString("en-GB");
      },
    },
    {
      data: null,
      render: (data, type, row) => {
        return `R$ ${Number(data.valor_total).toFixed(2)}`;
      },
    },
    {
      data: null,
      render: (data, type, row) => {
        return `R$ ${Number(data.valor_desconto).toFixed(2)}`;
      },
    },
    {
      data: null,
      render: (data, type, row) => {
        return `R$ ${Number(data.valor_final).toFixed(2)}`;
      },
    },
    {
      data: null,
      render: (data, type, row) => {
        return `R$ ${Number(data.valor_pago).toFixed(2)}`;
      },
    },
    {
      data: null,
      render: (data, type, row) => {
        if (data.valor_pago >= data.valor_final) {
          return `<center><b>Pagamento finalizado</b></center>`;
        } else {
          return `<center>
            <button type="button" class="btn btn-outline-success mb-1 btnReceber" valor_final="${
              data.valor_final
            }" valor_pago="${data.valor_pago ?? 0}" identificador=${
            data.identificador
          }>Receber pagamento</button>
          </center>`;
        }
      },
    },
  ],
  aoColumnDefs: [{ bSortable: false, aTargets: [7] }],
  order: [[2, "desc"]],
};

$(document).on("click", ".btnReceber", (event) => {
  let identificador = $(event.currentTarget).attr("identificador");
  let valor_final = $(event.currentTarget).attr("valor_final");
  let valor_pago = $(event.currentTarget).attr("valor_pago");
  let falta_receber = valor_final - valor_pago;

  Swal.fire({
    title: "Receber pagamento por venda",
    html:
      `<p>(Falta receber R$ ${Number(falta_receber).toFixed(2)})` +
      '<input id="swal-input1" class="swal2-input" placeholder="Insira o valor a receber" type="text">',
    focusConfirm: false,
    preConfirm: () => {
      return [document.getElementById("swal-input1").value];
    },
    willOpen: () => {
      $("#swal-input1").mask("##0,00", { reverse: true });
    },
  }).then((result) => {
    if (result.isConfirmed == true) {
      let valor_pago = result.value[0];
      let formData = new FormData();
      formData.append("identificador", identificador);
      formData.append("valor_pago", valor_pago);
      axios.post("Venda/updateValorPago", formData).then(({ data }) => {
        if (data.success == true) {
          window.location.reload();
        } else {
          Swal.fire("Oops", "Ocorreu um erro ao concretizar a venda.", "error");
        }
      });
    }
  });
});

const columnsProdutos = {
  columns: [
    { data: "produto" },
    { data: "estoque" },
    {
      data: null,
      render: (data, type, row) => {
        return `R$ ${Number(data.preco).toFixed(2)}`;
      },
    },
    {
      data: null,
      render: (data, type, row) => {
        return `<center>
          <button type="button" class="btn btn-outline-success mb-1 btnVender" identificador=${data.identificador} estoque=${data.estoque} preco=${data.preco} produto=${data.produto}><i class="fas fa-plus"></i></button>
        </center>`;
      },
    },
  ],
  aoColumnDefs: [{ bSortable: false, aTargets: [3] }],
};

$(document).ready(() => {
  rowVenda.hide();

  valor_total.mask("##0,00", { reverse: true });
  valor_desconto.mask("##0,00", { reverse: true });
  valor_final.mask("##0,00", { reverse: true });

  newVendaBtn.click(() => {
    rowPagamento.hide();
    rowVenda.show();
  });

  toggleVendas.click(() => {
    exibeVendasAntigas = !exibeVendasAntigas;
    toggleVendas.text(
      `${exibeVendasAntigas ? "Ocultar Vendas Pagas" : "Exibir Vendas Pagas"}`
    );

    $("#dataTableVendas").DataTable().destroy();
    dataTableVendas = new DataTableClass(
      "#dataTableVendas",
      "Venda/selectAll",
      { exibeVendasAntigas }
    );
    dataTableVendas.loadTable(columnsVendas);
  });

  returnBtn.click(() => {
    window.location.reload();
  });

  dataTableProdutos = new DataTableClass(
    "#dataTableProdutos",
    "Produtos/selectAllAtivosComEstoque",
    typeof localStorage.carrinho != "undefined"
      ? Object.keys(JSON.parse(localStorage.carrinho))
      : []
  );
  dataTableProdutos.loadTable(columnsProdutos);

  dataTableVendas = new DataTableClass("#dataTableVendas", "Venda/selectAll", {
    exibeVendasAntigas,
  });
  dataTableVendas.loadTable(columnsVendas);

  $(document).on("click", ".btnVender", (event) => {
    identificador = $(event.currentTarget).attr("identificador");
    estoque = $(event.currentTarget).attr("estoque");
    preco = $(event.currentTarget).attr("preco");
    produto = $(event.currentTarget).attr("produto");

    if (typeof localStorage.carrinho != "undefined") {
      carrinho = JSON.parse(localStorage.carrinho);
    } else {
      carrinho = {};
    }

    carrinho[identificador] = { estoque, preco, produto, quantidade: 1 };

    localStorage.carrinho = JSON.stringify(carrinho);
    updateEstoque();
    updateCarrinho();
  });

  axios.post("Clientes/listAll").then(({ data }) => {
    data.forEach((prd) => {
      cliente.append(
        $("<option></option>", {
          text: prd.nome,
          value: prd.identificador,
        })
      );
    });
  });

  valor_desconto.on("change", () => {
    updateCarrinho();
  });

  form.submit(() => {
    let formData = new FormData(form[0]);
    let items = [];

    $(".upspin").each((i, element) => {
      let produto = $(element).attr("identificador");
      let quantidade = $(element).val();
      let valor_unitario = $(element).attr("valor");
      items.push({ produto, quantidade, valor_unitario });
    });

    if (items.length == 0) {
      Swal.fire("Oops...", "Nenhum item foi incluído na venda ainda!", "error");
    } else {
      formData.append("items", JSON.stringify(items));
      axios.post("Venda/create", formData).then(({ data }) => {
        if (data.success == false) {
          Swal.fire(
            "Oops...",
            "Ocorreu um erro ao concretizar a venda!",
            "error"
          );
        } else {
          localStorage.carrinho = JSON.stringify({});
          window.location.reload();
        }
      });
    }
  });

  updateCarrinho();
});

function updateEstoque() {
  $("#dataTableProdutos").DataTable().destroy();
  dataTableProdutos = new DataTableClass(
    "#dataTableProdutos",
    "Produtos/selectAllAtivosComEstoque",
    typeof localStorage.carrinho != "undefined"
      ? Object.keys(JSON.parse(localStorage.carrinho))
      : []
  );
  dataTableProdutos.loadTable(columnsProdutos);
}

function updateCarrinho() {
  var valorTotal = 0;
  var valorDesconto;

  if (valor_desconto.val() == "") {
    valorDesconto = 0;
  } else {
    valorDesconto = valor_desconto.val().replace(".", "").replace(",", ".");
  }

  let tbody = tableCarrinho.find("tbody");

  if (typeof localStorage.carrinho != "undefined") {
    carrinho = JSON.parse(localStorage.carrinho);
  } else {
    carrinho = {};
  }

  tbody.html("");
  if (Object.keys(JSON.parse(localStorage.carrinho)).length == 0) {
    tbody.append("<tr><th colspan=4 class='text-center'>Nada ainda!</th></tr>");
  } else {
    Object.keys(JSON.parse(localStorage.carrinho)).forEach((identificador) => {
      let produto = carrinho[identificador];

      tbody.append(`
      <tr>
        <td>${produto.produto}</td>
        <td>R$ ${Number(produto.preco).toFixed(2)}</td>
        <td><input type="text" class="upspin" identificador="${identificador}" valor='${
        produto.preco
      }' name='quantidade${identificador}' required="true"></td>
        <td>R$ ${Number(produto.preco * produto.quantidade).toFixed(2)}</td>
      </tr>
      `);

      valorTotal += produto.preco * produto.quantidade;

      $(`[name="quantidade${identificador}"]`).TouchSpin({
        min: 0,
        max: produto.estoque,
        initval: produto.quantidade,
      });

      $(document).off("touchspin.on.stopdownspin", `.upspin`);
      $(document).on("touchspin.on.stopdownspin", `.upspin`, (event) => {
        target = $(event.currentTarget);

        if (typeof localStorage.carrinho != "undefined") {
          carrinho = JSON.parse(localStorage.carrinho);
        } else {
          carrinho = {};
        }

        if (target.val() == 0) {
          delete carrinho[target.attr("identificador")];
        } else {
          carrinho[target.attr("identificador")].quantidade = target.val();
        }

        localStorage.carrinho = JSON.stringify(carrinho);
        updateEstoque();
        updateCarrinho();
      });

      $(document).off("touchspin.on.stopupspin", `.upspin`);
      $(document).on("touchspin.on.stopupspin", `.upspin`, (event) => {
        target = $(event.currentTarget);
        if (typeof localStorage.carrinho != "undefined") {
          carrinho = JSON.parse(localStorage.carrinho);
        } else {
          carrinho = {};
        }

        carrinho[target.attr("identificador")].quantidade = target.val();
        localStorage.carrinho = JSON.stringify(carrinho);

        updateCarrinho();
      });
    });
  }

  valor_total.val(valorTotal.toFixed(2).replace(".", ","));
  valor_final.val((valorTotal - valorDesconto).toFixed(2).replace(".", ","));
}
