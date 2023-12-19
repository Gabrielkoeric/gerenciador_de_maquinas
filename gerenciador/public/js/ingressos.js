/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!***********************************!*\
  !*** ./resources/js/ingressos.js ***!
  \***********************************/
document.addEventListener("DOMContentLoaded", function () {
  var increaseButtons = document.querySelectorAll('.increase');
  var decreaseButtons = document.querySelectorAll('.decrease');
  var numberInputs = document.querySelectorAll('.number');
  var valorVendaElements = document.querySelectorAll('.valor-venda');
  var totalElement = document.getElementById('total');
  var totalValue = 0;
  var cart = [];
  increaseButtons.forEach(function (button, index) {
    button.addEventListener('click', function () {
      var estoqueId = button.getAttribute('data-estoque-id'); // Obtenha o estoque ID

      updateValue(numberInputs[index], 1, parseFloat(valorVendaElements[index].getAttribute('data-valorvenda')), estoqueId);
    });
  });
  decreaseButtons.forEach(function (button, index) {
    button.addEventListener('click', function () {
      var estoqueId = button.getAttribute('data-estoque-id'); // Obtenha o estoque ID

      updateValue(numberInputs[index], -1, parseFloat(valorVendaElements[index].getAttribute('data-valorvenda')), estoqueId);
    });
  });

  function updateValue(inputElement, multiplier, valorVenda, estoqueId) {
    var currentValue = Number(inputElement.value);
    var newValue = currentValue + multiplier;

    if (newValue >= 0) {
      // Certificar-se de que o valor não seja negativo
      inputElement.value = newValue; // Atualizar o valor total no footer

      updateTotalValue(multiplier, valorVenda); // Atualizar o carrinho de compras

      updateCart(estoqueId, newValue);
    }
  }

  function updateTotalValue(multiplier, valorVenda) {
    totalValue += multiplier * valorVenda;
    totalElement.innerText = "Total: R$ ".concat(totalValue.toFixed(2));
  }

  function updateCart(estoqueId, newValue) {
    // Verifique se o item já existe no carrinho
    var existingItem = cart.find(function (item) {
      return item.id_estoque === estoqueId;
    });

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
  } // Lógica para enviar os dados do carrinho para a rota de compra.store


  var finalizarPedidoButton = document.querySelector('button.btn-primary');
  finalizarPedidoButton.addEventListener('click', function () {
    if (cart.length > 0) {
      var formData = new FormData(); // Adicione cada item do carrinho ao formData

      cart.forEach(function (item) {
        formData.append('_token', item._token);
        formData.append('id_estoque[]', item.id_estoque);
        formData.append('quantidadeInicial[]', item.quantidadeInicial);
      }); // Construa a URL de forma convencional

      var url = compraStoreRoute;
      fetch(url, {
        method: 'POST',
        body: formData
      }).then(function (response) {
        return response.json();
      }).then(function (data) {
        window.location.href = '/estoque';
      })["catch"](function (error) {//console.error('Erro ao enviar os dados do carrinho:', error);
        // window.location.href = '/checkout';
      });
    }
  });
});
/******/ })()
;