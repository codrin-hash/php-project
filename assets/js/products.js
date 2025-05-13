document.addEventListener('DOMContentLoaded', () => {
    const createForm = document.getElementById('create-form');
    const editForm = document.getElementById('edit-form');
    const createMsg = document.getElementById('create-message');
    const editMsg = document.getElementById('edit-message');

    function handleFormSubmit(form, messageDiv) {
        const formData = new FormData(form);

        fetch('save_product.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            messageDiv.innerHTML = '';
            if (data.success) {
                messageDiv.innerHTML = `<span style="color: green;">Product saved successfully.</span>`;
                form.reset();
            } else if (data.errors) {
                const list = data.errors.map(e => `<li>${e}</li>`).join('');
                messageDiv.innerHTML = `<ul style="color: red;">${list}</ul>`;
            } else {
                messageDiv.innerHTML = `<span style="color: red;">Unexpected error.</span>`;
            }
        })
        .catch(() => {
            messageDiv.innerHTML = `<span style="color: red;">Network/server error.</span>`;
        });
    }

    if (createForm) {
        createForm.addEventListener('submit', e => {
            e.preventDefault();
            handleFormSubmit(createForm, createMsg);
        });
    }

    if (editForm) {
        editForm.addEventListener('submit', e => {
            e.preventDefault();
            handleFormSubmit(editForm, editMsg);
        });
    }
});
