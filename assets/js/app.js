document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = 'csrf_token';
        csrf.value = form.dataset.csrf || document.body.dataset.csrf || '';
        if (csrf.value && !form.querySelector('input[name="csrf_token"]')) {
            form.appendChild(csrf);
        }
    });

    const confirmButtons = document.querySelectorAll('[data-confirm]');
    confirmButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const message = button.dataset.confirm || 'Tem certeza?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});
