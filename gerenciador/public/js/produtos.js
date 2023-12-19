/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!**********************************!*\
  !*** ./resources/js/produtos.js ***!
  \**********************************/
document.addEventListener("DOMContentLoaded", function () {
  var increaseButtons = document.querySelectorAll('.increase');
  var decreaseButtons = document.querySelectorAll('.decrease');
  var numberInputs = document.querySelectorAll('.number');
  var totalValue = 0;
  increaseButtons.forEach(function (button, index) {
    button.addEventListener('click', function () {
      updateValue(numberInputs[index], 1);
    });
  });
  decreaseButtons.forEach(function (button, index) {
    button.addEventListener('click', function () {
      updateValue(numberInputs[index], -1);
    });
  });

  function updateValue(inputElement, multiplier) {
    var newValue = Number(inputElement.value) + multiplier;

    if (newValue >= 0) {
      // Certificar-se de que o valor não seja negativo
      inputElement.value = newValue; // Atualizar o valor total no footer

      updateTotalValue();
    }
  }

  function updateTotalValue() {
    var newTotalValue = 0;
    numberInputs.forEach(function (input) {
      newTotalValue += Number(input.value);
    });
    totalValue = newTotalValue; // Atualizar o total no footer, se necessário
    // const totalElement = document.getElementById('total');
    // totalElement.innerText = `Total: R$ ${totalValue.toFixed(2)}`;
  }
});
/******/ })()
;