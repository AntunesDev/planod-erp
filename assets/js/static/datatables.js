function DataTableClass(tableName, endPoint, args) {
  var Data = {};

  this.loadTable = function (loadAjax) {
    this.appendData(this.processing);
    this.appendData(this.ajax);
    this.appendData(loadAjax);
    this.appendData(this.language);
    this.appendData(this.init);
    $($(tableName)).dataTable(Data);
    $(".dataTables_filter input").attr("placeholder", "Filtrar");
  };

  this.appendData = function (data) {
    $.extend(Data, data);
    return Data;
  };

  this.processing = {
    processing: true,
    serverSide: true,
  };

  this.ajax = {
    ajax: {
      url: endPoint,
      type: "POST",
      dataType: "json",
      data: function (d) {
        d.postData = args;
      },
      dataSrc: function (dataJson) {
        return dataJson.records;
      },
    },
  };

  this.language = {
    language: {
      emptyTable: "Não tem dados disponíveis na Tabela",
      info: "Mostrando _START_ a _END_ de _TOTAL_ resultados",
      infoEmpty: "Mostrando 0 a 0 de 0 resultados",
      infoFiltered: "(Filtrado de um total de _MAX_ resultados)",
      lengthMenu: "Mostrar _MENU_ resultados",
      loadingRecords: "Carregando...",
      processing: "Processando...",
      zeroRecords: "A pesquisa não retornou nenhum resultado",
      paginate: {
        first: "Primeiro",
        last: "Último",
        next: "Próximo",
        previous: "Anterior",
      },
      search: "",
    },
  };

  this.init = {
    initComplete: function () {
      $(tableName + "_filter input").unbind();
      $(tableName + "_filter input").bind("keyup", function (e) {
        if (e.keyCode == 13) {
          $($(tableName)).DataTable().search(this.value).draw();
        }
      });
    },
  };
}
