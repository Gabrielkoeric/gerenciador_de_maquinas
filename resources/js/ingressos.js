document.addEventListener("DOMContentLoaded", function () {
    const increaseButtons = document.querySelectorAll('.increase');
    const decreaseButtons = document.querySelectorAll('.decrease');
    const numberInputs = document.querySelectorAll('.number');
    const valorVendaElements = document.querySelectorAll('.valor-venda');
    const totalElement = document.getElementById('total');
    let totalValue = 0;
    let cart = [];

    increaseButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const estoqueId = button.getAttribute('data-estoque-id'); // Obtenha o estoque ID
            updateValue(numberInputs[index], 1, parseFloat(valorVendaElements[index].getAttribute('data-valorvenda')), estoqueId);
        });
    });

    decreaseButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            const estoqueId = button.getAttribute('data-estoque-id'); // Obtenha o estoque ID
            updateValue(numberInputs[index], -1, parseFloat(valorVendaElements[index].getAttribute('data-valorvenda')), estoqueId);
        });
    });

    function updateValue(inputElement, multiplier, valorVenda, estoqueId) {
        const currentValue = Number(inputElement.value);
        const newValue = currentValue + multiplier;

        if (newValue >= 0) {  // Certificar-se de que o valor não seja negativo
            inputElement.value = newValue;

            // Atualizar o valor total no footer
            updateTotalValue(multiplier, valorVenda);

            // Atualizar o carrinho de compras
            updateCart(estoqueId, newValue);
        }
    }

    function updateTotalValue(multiplier, valorVenda) {
        totalValue += multiplier * valorVenda;
        totalElement.innerText = `Total: R$ ${totalValue.toFixed(2)}`;
    }

    function updateCart(estoqueId, newValue) {
        // Verifique se o item já existe no carrinho
        const existingItem = cart.find(item => item.id_estoque === estoqueId);

        if (existingItem) {
            // Atualize a quantidade do item no carrinho
            existingItem.quantidadeInicial = newValue;
        } else {
            // Adicione um novo item ao carrinho
            cart.push({
                _token: csrfToken,
                id_estoque: estoqueId,
                quantidadeInicial: newValue
            });
        }
    }

    // Lógica para enviar os dados do carrinho para a rota de compra.store
    const finalizarPedidoButton = document.querySelector('button.btn-primary');
    finalizarPedidoButton.addEventListener('click', function () {
        if (cart.length > 0) {
            const formData = new FormData();

            // Adicione cada item do carrinho ao formData
            cart.forEach(item => {
                formData.append('_token', item._token);
                formData.append('id_estoque[]', item.id_estoque);
                formData.append('quantidadeInicial[]', item.quantidadeInicial);
            });

            // Construa a URL de forma convencional
            const url = compraStoreRoute;

            fetch(url, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    window.location.href = '/estoque';
                })
                .catch(error => {
                    //console.error('Erro ao enviar os dados do carrinho:', error);
                    // window.location.href = '/checkout';
                });
        }
    });
});
