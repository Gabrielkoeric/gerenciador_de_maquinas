document.addEventListener("DOMContentLoaded", function () {
    const increaseButtons = document.querySelectorAll('.increase');
    const decreaseButtons = document.querySelectorAll('.decrease');
    const numberInputs = document.querySelectorAll('.number');
    let totalValue = 0;

    increaseButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            updateValue(numberInputs[index], 1);
        });
    });

    decreaseButtons.forEach((button, index) => {
        button.addEventListener('click', () => {
            updateValue(numberInputs[index], -1);
        });
    });

    function updateValue(inputElement, multiplier) {
        const newValue = Number(inputElement.value) + multiplier;
        if (newValue >= 0) {  // Certificar-se de que o valor não seja negativo
            inputElement.value = newValue;
            // Atualizar o valor total no footer
            updateTotalValue();
        }
    }

    function updateTotalValue() {
        let newTotalValue = 0;
        numberInputs.forEach((input) => {
            newTotalValue += Number(input.value);
        });
        totalValue = newTotalValue;
        // Atualizar o total no footer, se necessário
        // const totalElement = document.getElementById('total');
        // totalElement.innerText = `Total: R$ ${totalValue.toFixed(2)}`;
    }
});
