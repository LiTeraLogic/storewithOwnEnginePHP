function addGoods(id) {
    jQuery.ajax({
        //url: 'http://pubgb/?p=basket&a=addAjax&id=' + id,
        url: '?p=basket&a=addAjax&id=' + id,
        type: 'POST',
        data: {asd: 234, sdfsdfg:234},
        success: function (response) {
            if (!response.success) {
                return  jQuery('#msg').text(response.error);
            }
            jQuery('#msg').text('Товар добавлен в корзину');
            jQuery('#countInBasket').text(response.countInBasket);
        }
    });
}

/*function outLog(id) {
    jQuery.ajax({
        url: '?p=register',
        type: 'POST',
        data: {asd: 234, sdfsdfg:234},
        success: function (response) {
            if (!response.success) {
                return;
            }
            jQuery('#msg').text('Презде чем купить товар, зарегистрируйтесь');
        }
    });
}*/