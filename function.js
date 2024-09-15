document.querySelectorAll('table input').forEach(input => {
    input.addEventListener('focus', function () {
        this.placeholder = '';
    });

    input.addEventListener('blur', function () {
        if (this.value === '') {
            this.placeholder = this.getAttribute('data-default');
        }
    });

    // Set initial placeholder
    if (input.value === '') {
        input.placeholder = input.getAttribute('data-default');
    }
});

document.addEventListener("DOMContentLoaded", function () {
    // Mặc định hiển thị tất cả các sản phẩm khi trang được tải
    console.log("DOM content loaded");


    // Hàm lọc sản phẩm dựa trên thương hiệu được chọn
    function filterBrands(brand) {
        var products = document.querySelectorAll(".product-item");

        resetProducts();
        // Áp dụng bộ lọc cho sản phẩm dựa trên thương hiệu
        console.log(brand);
        for (var i = 0; i < products.length; i++) {
            var productBrand = products[i].querySelector('.product-brand').getAttribute('data-brand');
            if (brand.toLowerCase() !== "all" && productBrand.toLowerCase() !== brand.toLowerCase()) {
                products[i].classList.add("hidden");
            } else {
                products[i].classList.remove("hidden");
            }
        }

    }

    function resetProducts() {
        // Lấy danh sách tất cả các sản phẩm
        var products = document.querySelectorAll(".product-item");

        // Lặp qua từng sản phẩm và kiểm tra thuộc tính display của nó
        products.forEach(function (product) {
            product.classList = "product-item";
        });
        console.log("testing");
    }

    // Xử lý sự kiện khi người dùng nhấp vào các nút lọc thương hiệu
    var btns = document.querySelectorAll("#btnBrands .btn");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function () {
            var current = document.querySelector("#btnBrands .active");
            if (current) {
                current.classList.remove("active");
            }
            this.classList.add("active");
            filterBrands(this.textContent.trim());
        });
    }

    var selectElement = document.getElementById("cate-filter");

    selectElement.addEventListener("change", function () {
        var selectedValue = this.value;
        filterCate(selectedValue);
    });

    function filterCate(cate_name) {
        var products = document.querySelectorAll(".product-item");
        for (var i = 0; i < products.length; i++) {
            var productCate = products[i].querySelector(".product-cate").getAttribute("data-cate");
            if (cate_name.toLowerCase() !== "all" && productCate.toLowerCase() !== cate_name.toLowerCase()) {
                products[i].classList.add("hidden");
            } else {
                products[i].classList.remove("hidden");
            }
        }
    }


    // Lấy thẻ button "Apply Filter"
    var applyPriceFilterBtn = document.getElementById("apply-price-filter");

    // Thêm sự kiện click cho button "Apply Filter"
    applyPriceFilterBtn.addEventListener("click", function () {
        var minPrice = parseFloat(document.getElementById("min-price").value);
        var maxPrice = parseFloat(document.getElementById("max-price").value);

        applyPriceFilter(minPrice, maxPrice);
    });


    // Hàm áp dụng bộ lọc theo giá
    function applyPriceFilter(minPrice, maxPrice) {
        // Lặp qua danh sách các sản phẩm và ẩn hoặc hiển thị tùy thuộc vào giá trị giữa minPrice và maxPrice
        var products = document.querySelectorAll(".product-item");
        for (i = 0; i < products.length; i++) {
            var priceElement = products[i].querySelector('.product-price').getAttribute('data-price');
            var priceText = priceElement.trim();
            var price = parseFloat(priceText.replace("$", "").replace(",", ""));
            if (!isNaN(price) && price < minPrice || price > maxPrice) {
                products[i].classList.add("hidden");
            } else {
                products[i].classList.remove("hidden");
            }
        }
    }

});

// -----------SEARCH DEVICE------------
document.getElementById("searchInput").addEventListener("keydown", function (event) {
    if (event.keyCode === 13) { // 13 is the Enter key
        event.preventDefault(); // Prevent the default form submission
        handleSearch();
    }
});

function handleSearch() {
    var searchTerm = document.getElementById("searchInput").value;
    if (searchTerm.trim() !== "") {
        window.location.href = "search_result.php?search=" + encodeURIComponent(searchTerm);
    }
    return false;
}





