$(function($) {
    var $navigationShow = $('.navigation-show'),
        $navigationHide = $('.navigation-hide'),
        $categories = $('.categories');

    $navigationHide.on('click', function() {
        $navigationShow.show();
        $categories.hide();
    });
    $navigationShow.on('click', function() {
        $navigationShow.hide();
        $categories.show();
    });

    var $search = $('#search'),
        $body = $('.body');
        page = 1;

    $search.on('keypress', function(e) {
        if (e.keyCode != 13) {
            return;
        }
        loadProducts();
    });

    loadProducts();

    function loadProducts() {
        $.getJSON(productsUrl, {search: $search.val(), page: getPage()})
            .then(function(products) {
                clearProducts();
                appendProducts(products);
            })
            .fail(function() {
                console.log('Пришла ошибка');
            })
    }

    function getPage() {
        return page;
    }

    function clearProducts() {
        $body.empty();
    }

    function appendProducts(products) {
        for (var i = 0; i < products.length; i++) {
            createProduct(products[i]).appendTo($body);
        }
    }

    function createProduct(product) {
        return $('<div>')
            .addClass('product')
            .append($('<img>').attr('src', product.photos[0]))
            .append(product.name);
    }
});