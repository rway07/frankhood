/**
 * @file deliveries/delivery.js
 * @author kain rway07@gmail.com
 */
import { showGuruModal, showModal } from '../common/notifications.js';
import { FormManager } from './form.js';

export class DeliveryController {
    #date;
    #total;
    #amount;
    #remaining;
    #manager;

    constructor() {
        this.#date = '';
        this.#amount = '';
        this.#total = 0;
        this.#remaining = 0;
        this.#manager = new FormManager();
    }

    setDate(date) {
        this.#date = date;
        this.getTotal();
    }

    setAmount(amount) {
        if (v8n().numeric().test(amount)) {
            this.#amount = amount;
            this.#computeRemaining();
        } else {
            this.#amount = '';
            this.#manager.disableForm();
            this.#manager.showErrorMessage('La cifra deve essere numerica');
        }
    }

    getTotal() {
        const url = `/api/deliveries/${this.#date}/total`;

        fetch(url)
            .then((response) => {
                if (!response.ok) {
                    return Promise.reject(response);
                }

                return response.json()
            })
            .then((response) => {
                if ('error' in response) {
                    showModal(response.error.message);
                    return false;
                }

                if ('data' in response) {
                    this.#total = response.data.total;
                    this.#manager.drawTotal(this.#total);

                    if (this.#total === 0) {
                        this.#manager.lockInput('Non ci sono contanti disponibiili nella data selezionata');
                    } else {
                        this.#manager.unlockInput();
                        this.#computeRemaining();
                    }

                    return true;
                }

                return false;
            })
            .catch(error => {
                if (error instanceof Response) {
                    error.json().then(data => {
                        showGuruModal(error.status, error.statusText, data.error.message)
                    });
                }
            });
    }

    #computeRemaining() {
        this.#remaining = this.#total - this.#amount;
        this.#manager.drawRemaining(this.#remaining);

        if ((this.#date === '') || (this.#amount === '')) {
            this.#manager.disableForm();
        } else if (this.#remaining < 0) {
            this.#manager.disableForm();
            this.#manager.showErrorMessage('Cifra non valida');
        } else {
            this.#manager.enableForm();
            this.#manager.resetErrorMessage();
        }
    }
}
