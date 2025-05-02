$(document).ready(function() {
    //Abrir carrinho
    $('#btn-carrinho').on('click', function() {
        $('#espaco-carrinho').css({
            display: 'inline-block'
        })
    })
    //Fechar carrinho
    $('#fechar-carrinho').on('click', function() {
        $('#espaco-carrinho').css({
            display: 'none'
        })
    })
    $('#produtos-container').on('click', function() {
        $('#espaco-carrinho').css({
            display: 'none'
        })
    })

    //adicionar produto ao carrinho
    $(document).on('click', '.adicionar', function(event) {
        event.preventDefault()
        let product_id = $(this).data('id')
        console.log(product_id)
        $.ajax({
            url: '../php/carrinho.php',
            method: 'POST',
            data: {product_id: product_id},
            success: function(response) {
                console.log(response)
                carregarCarrinho()
            },
            error: function(xhr, status, error) {
                console.error('Erro ao adicionar produto: ', error)
                console.error('Erro xhr: ', xhr.responseText)
                console.error('Status: ', status)
            }
        })
    })

    function carregarCarrinho() {
        $.ajax({
            url: '../php/carrinho.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                let tbody = $('#tbody_carrinho')
                tbody.html('');
                let total = 0
                $.each(data, function(index, item) {
                    total += item.price * item.quantity
                    let tr = `
                        <tr class="mb-2">
                            <td class="image_title">
                                <img src="${item.image}" class="img-fluid" alt="${item.name}">
                                <p class="d-lg-inline-block">${item.name}</p>
                            </td>
                            <td class="td_price">
                                <p>€${item.price}</p>
                            </td>
                            <td class="td_qtd">
                                <input type="number" value="${item.quantity}" min="1" class="qtd_carrinho" data-id="${item.product_id}" style="background-color: black">
                            </td>
                            <td class="td_remove">
                                <button type="button" class="btn btn-danger remover-btn" data-id="${item.product_id}">
                                    <p>Remover</p>
                                </button>
                            </td>
                        </tr>
                    `;
                    tbody.append(tr)
                })
                $('#total').text('Total: €' + total.toFixed(2))
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar o carrinho: ', error)
                console.error('Erro xhr: ', xhr.responseText)
                console.error('Status: ', status)
            }
        })
    }

    carregarCarrinho()

    //atualizando a quantidade do produto no carrinho
    $(document).on('focus', '.qtd_carrinho', function() {
        $(this).data('oldValue', $(this).val())
    })
    $(document).on('change', '.qtd_carrinho', function() {
        let productId = $(this).data('id')
        let newQuantity = $(this).val()
        let oldValue = $(this).data('oldValue')
        console.log(productId + ' ID')
        console.log(newQuantity + ' QTD')
        console.log(oldValue + ' OLD')
        if(newQuantity === '' || newQuantity <= 0 || isNaN(newQuantity)) {
            $(this).val(oldValue)
            return
        }

        $.ajax({
            url: '../php/atualizar_carrinho.php',
            type: 'POST',
            data: {product_id: productId, quantity: newQuantity},
            success: function(response) {
                console.log(response)
                carregarCarrinho()
            },
            error: function(xhr, status, error) {
                console.error('Erro ao atulizar o carrinho: ', error)
                console.error('Erro xhr: ', xhr)
                console.error('Status: ', status)
            }
        })
    })

    //removendo do carrinho e do banco de dados com DELETE
    $(document).on('click', '.remover-btn', function() {
        let productId = $(this).data('id')

        $.ajax({
            url: '../php/atualizar_carrinho.php',
            type: 'POST',
            data: {product_id: productId, quantity: 0},
            success: function(response) {
                carregarCarrinho()
                console.log(response)
            },
            error: function(xhr, status, error) {
                console.error('Erro ao remover do carrinho: ', error)
            }
        })
    })

    //finalizando a compra
    $('#finalizar').on('click', function() {
        let total = $('#total').text()
        total = total.replace('Total: €', '')
        total = parseFloat(total)

        $.ajax({
            url: '../php/finalizar_compra.php',
            method: 'POST',
            data: {total: total},
            success: function(response) {
                alert(response)
                $('#espaco-carrinho').css('display', 'none')
                carregarCarrinho()
            }
        })
    })

/*    
    //ajax para carregar produtos para o carrinho
    function carregar() {
       $.ajax({
        url: '../php/carrinho.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if(data.error) {
                console.error(data.error)
                return
            }

            let tbody = $('#tbody_carrinho');
            let total = 0;
            $.each(data, function(index, item) {
                total += item.price * item.quantity
                console.log(item)
                console.log(item.price)

                let tr = `
                    <tr>
                        <td>
                            <img src="${item.image}" alt="${item.name}" width="75">
                            <p>${item.name}</p>
                        </td>
                        <td>
                            <p>${item.price}</p>
                        </td>
                        <td>
                            <input type="number" value="${item.quantity}" min="1" class="qtd_carrinho" data-id="${item.product_id}" style="background-color: black">
                        </td>
                        <td>
                            <button type="button" class="remover-btn" data-id="${item.product_id}">Remover</button>
                        </td>
                    </tr>
                `;
                tbody.append(tr);
            })
            //atualizando o total no DOM
            $('#total').text('Total: €' + total.toFixed(2))
        },
        error: function(xhr, status, error) {
            console.error('Erro ao carregar o carrinho: ', error);
            console.error('Erro xhr: ', xhr.responseText)
            console.error('Status: ', status)
        }
       })
    }

    carregar()

    //atualizando a quantidade do produto no carrinho
    $(document).on('focus', '.qtd_carrinho', function() {
        $(this).data('oldValue', $(this).val())
    })
    $(document).on('change', '.qtd_carrinho', function() {
        let productId = $(this).data('id')
        let newQuantity = $(this).val()
        let oldValue = $(this).data('oldValue')

        if(newQuantity === '' || newQuantity <= 0) {
            $(this).val(oldValue)
            return
        }

        $.ajax({
            url: '../php/atualizar_carrinho.php',
            type: 'POST',
            data: {product_id: productId, quantity: newQuantity},
            success: function(response) {
                console.log(response)
            },
            error: function(xhr, status, error) {
                console.error('Erro ao atulizar o carrinho: ', error)
                console.error('Erro xhr: ', xhr)
                console.error('Status: ', status)
            }
        })
    })

    //removendo do carrinho e do banco de dados com DELETE
    $(document).on('click', '.remover-btn', function() {
        let productId = $(this).data('id')

        $.ajax({
            url: '../php/atualizar_carrinho.php',
            type: 'POST',
            data: {product_id: productId, quantity: 0},
            success: function(response) {
                console.log(response)
            },
            error: function(xhr, status, error) {
                console.error('Erro ao remover do carrinho: ', error)
            }
        })
    })

    //finalizando a compra
    $('#finalizar').on('click', function() {
        $.ajax({
            url: '../php/finalizar_compra.php',
            method: 'POST',
            success: function(response) {
                alert(response)
                carregar()
            }
        })
    })
*/
})