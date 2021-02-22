const relLucratividade = $("#relLucratividade");
const relMovEstoque = $("#relMovEstoque");
const relCustoEstoque = $("#relCustoEstoque");
const relVendasCliente = $("#relVendasCliente");

$(document).ready(() => {
  $(".input-daterange").datepicker({
    format: "dd/mm/yyyy",
    autoclose: true,
    todayHighlight: true,
    todayBtn: "linked",
    language: "pt-BR",
  });

  relLucratividade.submit(() => {
    let formData = new FormData(relLucratividade[0]);
    axios
      .post("Relatorios/relatorioLucratividade", formData)
      .then(({ data }) => {
        if (data.success == false) {
          Swal.fire(
            "Oops...",
            "Não existem informações o suficiente para emitir um relatório!",
            "error"
          );
        } else {
          printHTML(data);
        }
      });
  });

  relMovEstoque.submit(() => {
    let formData = new FormData(relMovEstoque[0]);
    axios.post("Relatorios/relatorioMovEstoque", formData).then(({ data }) => {
      if (data.success == false) {
        Swal.fire(
          "Oops...",
          "Não existem informações o suficiente para emitir um relatório!",
          "error"
        );
      } else {
        printHTML(data);
      }
    });
  });

  relCustoEstoque.submit(() => {
    let formData = new FormData(relCustoEstoque[0]);
    axios
      .post("Relatorios/relatorioCustoEstoque", formData)
      .then(({ data }) => {
        if (data.success == false) {
          Swal.fire(
            "Oops...",
            "Não existem informações o suficiente para emitir um relatório!",
            "error"
          );
        } else {
          printHTML(data);
        }
      });
  });

  relVendasCliente.submit(() => {
    let formData = new FormData(relVendasCliente[0]);
    axios
      .post("Relatorios/relatorioVendasPorCliente", formData)
      .then(({ data }) => {
        if (data.success == false) {
          Swal.fire(
            "Oops...",
            "Não existem informações o suficiente para emitir um relatório!",
            "error"
          );
        } else {
          printHTML(data);
        }
      });
  });
});

function printHTML(htmlToPrint) {
  window.document.write(htmlToPrint);
  window.print();
  location.reload();
}
