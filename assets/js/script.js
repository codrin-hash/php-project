document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const searchInput = form.querySelector("input[name='search']");
    const productList = document.getElementById("product-list");

    // Functia AJAX de incarcare a produselor
    function loadProducts(page = 1) {
        const search = searchInput.value;
        const url = `products_ajax.php?search=${encodeURIComponent(search)}&page=${page}`;
        fetch(url)
            .then(res => res.text())
            .then(html => {
                productList.innerHTML = html;
                attachPaginationHandlers();
            });
    }

    // AJAX pe search
    form.addEventListener("submit", (e) => {
        e.preventDefault();
        loadProducts(1);
    });

    // Pagination handler (reatasat dupa fiecare request)
    function attachPaginationHandlers() {
        const pageLinks = productList.querySelectorAll(".pagination a[data-page]");
        pageLinks.forEach(link => {
            link.addEventListener("click", (e) => {
                e.preventDefault();
                const page = link.getAttribute("data-page");
                loadProducts(page);
            });
        });
    }

    attachPaginationHandlers();
});
