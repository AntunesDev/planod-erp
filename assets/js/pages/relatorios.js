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
    Swal.fire("Calma, cocada!", "Isso ainda não tá pronto.", "error");
  });

  relCustoEstoque.submit(() => {
    let formData = new FormData(relCustoEstoque[0]);
    Swal.fire("Calma, cocada!", "Isso ainda não tá pronto.", "error");
  });

  relVendasCliente.submit(() => {
    let formData = new FormData(relVendasCliente[0]);
    Swal.fire("Calma, cocada!", "Isso ainda não tá pronto.", "error");
  });
});

function printHTML(html) {
  let mywindow = window.open('localhost', 'PRINT', 'height=650,width=900,top=100,left=150');

  $(mywindow.document.body).html(html);
  mywindow.document.close();
  mywindow.focus();
  mywindow.print();
  mywindow.close();

  return true;
}