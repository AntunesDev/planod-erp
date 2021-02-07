const divProdutos = $('#divProdutos');
const divVendas = $('#divVendas');
const divCobrancas = $('#divCobrancas');
const divClientes = $('#divClientes');
const divEstoqueWarning = $('#divEstoqueWarning');

$(document).ready(() => {
    axios.post("Home/getIndexTotalizers").then(({ data }) => {
        divProdutos.text(data.produtos);
        divVendas.text(data.vendas);
        divCobrancas.text(data.cobrancas);
        divClientes.text(data.clientes);
    })

    axios.post("Home/getLowStock").then(({ data }) => {
        data.forEach(element => {
            if (element.quantidade <= 10) {
                divEstoqueWarning.append(`
                <div class="customer-message align-items-center">
                    <a class="font-weight-bold" href="#">
                        <div class="text-truncate message-title">O produto '${element.descricao}' possui apenas ${element.quantidade} unidade(s) em estoque!</div>
                        <div class="small text-gray-500 message-time font-weight-bold">Última Movimentação: ${new Date(
                    Date.parse(element.ultima_movimentacao)
                ).toLocaleString("en-GB")}</div >
                    </a >
                </div > `);
            }
        });
    })
})