document.addEventListener('DOMContentLoaded', () => {
    const editButtons = document.querySelectorAll('.edit-btn');
    const saveButtons = document.querySelectorAll('.save-btn');
    const cancelButtons = document.querySelectorAll('.cancel-btn');

    editButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const field = btn.dataset.field;
            const input = document.querySelector(`input[name="${field}"]`);
            input.removeAttribute('readonly');

            btn.style.display = 'none';
            const saveBtn = document.querySelector(`.save-btn[data-field="${field}"]`);
            saveBtn.style.display = 'inline-block';

            const cancelBtn = document.querySelector(`.cancel-btn[data-field="${field}"]`);
            cancelBtn.style.display = 'inline-block';
        });
    });

    saveButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const field = btn.dataset.field;
            const input = document.querySelector(`input[name="${field}"]`);
            const value = input.value;

            fetch('/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({field, value}),
            })
                .then(response => response.json())
                .then(data => {
                    input.setAttribute('readonly', 'readonly');

                    btn.style.display = 'none';
                    const editBtn = document.querySelector(`.edit-btn[data-field="${field}"]`);
                    editBtn.style.display = 'inline-block';

                    const cancelBtn = document.querySelector(`.cancel-btn[data-field="${field}"]`);
                    cancelBtn.style.display = 'none';
                })
                .catch(error => console.error('Ошибка:', error));
        });
    });

    cancelButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const field = btn.dataset.field;
            const input = document.querySelector(`input[name="${field}"]`);
            input.setAttribute('readonly', 'readonly');

            btn.style.display = 'none';
            const editBtn = document.querySelector(`.edit-btn[data-field="${field}"]`);
            editBtn.style.display = 'inline-block';

            const saveBtn = document.querySelector(`.save-btn[data-field="${field}"]`);
            saveBtn.style.display = 'none';
        });
    });
});