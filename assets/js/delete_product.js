document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.getElementById('product-table-body');

    tableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('delete-btn')) {
            const id = e.target.dataset.id;
            console.log("ID to delete:", id);
            const row = e.target.closest('tr');

            const confirmDelete = confirm('Are you sure you want to delete this product?');
            if (!confirmDelete) return;

            fetch('delete_product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'id=' + encodeURIComponent(id)
            })
            .then(response => {
                if (!response.ok) throw new Error("HTTP error");
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    row.remove();
                    alert('Product deleted successfully!');
                } else {
                    alert('Error: ' + (data.error || 'Failed to delete product.'));
                }
            })
            .catch(error => {
                alert('Server error or invalid response.');
                console.error('Fetch error:', error);
            });
        }
    });
});
