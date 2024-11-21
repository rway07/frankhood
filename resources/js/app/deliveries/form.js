/**
 * @file deliveries/form.js
 * @author kain rway07@gmail.com
 */

export class FormManager {
    #amountSelector;
    #totalSelector;
    #remainingSelector;
    #buttonSelector;
    #errorSelector;
    #formEnabled;
    #inputLocked;

    constructor() {
        this.#amountSelector = document.getElementById('amount');
        this.#totalSelector = document.getElementById('total');
        this.#remainingSelector = document.getElementById('remaining');
        this.#buttonSelector = document.getElementById('delivery-button');
        this.#errorSelector = document.getElementById('alert');
        this.#formEnabled = false;
        this.#inputLocked = false;
    }

    drawTotal(total) {
        this.#totalSelector.value = total;
    }

    drawRemaining(remaining) {
        this.#remainingSelector.value = remaining;
    }

    disableForm() {
        if (this.#formEnabled) {
            this.#formEnabled = false;
            this.#buttonSelector.setAttribute('disabled', 'disabled');
        }
    }

    enableForm() {
        if (!this.#formEnabled) {
            this.#formEnabled = true;
            this.#buttonSelector.removeAttribute('disabled');
        }
    }

    lockInput(message) {
        if (!this.#inputLocked) {
            this.#inputLocked = true;
            this.#amountSelector.setAttribute('disabled', 'disabled');
            this.showErrorMessage(message);
            this.disableForm();
        }
    }

    unlockInput() {
        if (this.#inputLocked) {
            this.#inputLocked = false;
            this.#amountSelector.removeAttribute('disabled');
            this.resetErrorMessage();
            this.enableForm();
        }
    }

    showErrorMessage(message) {
        this.#errorSelector.textContent = message;
        this.#errorSelector.className = 'badge text-bg-danger';
    }

    resetErrorMessage() {
        this.#errorSelector.textContent = '';
        this.#errorSelector.className = '';
    }
}
