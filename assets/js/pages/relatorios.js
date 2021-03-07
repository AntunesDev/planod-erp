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
    printReport("Relatorios/relatorioLucratividade", formData);
  });

  relMovEstoque.submit(() => {
    let formData = new FormData(relMovEstoque[0]);
    printReport("Relatorios/relatorioMovEstoque", formData)
  });

  relCustoEstoque.submit(() => {
    let formData = new FormData(relCustoEstoque[0]);
    printReport("Relatorios/relatorioCustoEstoque", formData)
  });

  relVendasCliente.submit(() => {
    let formData = new FormData(relVendasCliente[0]);
    printReport("Relatorios/relatorioVendasPorCliente", formData)
  });
});

const printReport = (method, formData) => {
  axios.post(method, formData, { responseType: "arraybuffer" }).then(response => {
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
};