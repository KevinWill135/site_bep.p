$(document).ready(function() {

    //filtrar produtos
    $('#tipo, .precos').on('change', function() {
        filtrarProdutos()
    })

    function filtrarProdutos() {
        let tipo = $('#tipo').val()
        let precos = []

        $('.precos:checked').each(function() {
            precos.push($(this).attr('id'))
        })

        $.ajax({
            url: '../php/process_store.php',
            method: 'POST',
            data: {tipo: tipo, precos: precos},
            dataType: 'html',
            success: function(response)  {
                $('#produtos-container').html(response)
            },
            error: function(xhr, status, error) {
                console.error('Erro ao filtrar produtos: ', error)
                console.error('Erro xhr: ', xhr.responseText)
                console.error('Status: ', status)
            }
        })
    }
/*
    //carregar produtos filtrados
    $('#tipo, .precos').on('change', function() {
        let tipo = $('#tipo').val()
        let precos = []
        console.log(tipo)
        $('.precos:checked').each(function() {
            precos.push($(this).attr('id'))
            console.log(precos)

            $.ajax({
                url: '../php/process_store.php',
                method: 'POST',
                data: {tipo: tipo, precos: precos},
                success: function(response)  {
                    $('#produtos-container').html(response)
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao filtrar produtos: ', error)
                    console.error('Erro xhr: ', xhr.responseText)
                    console.error('Status: ', status)
                }
            })
        })
    })*/

/*
    function carregarCarrinho() {
        $.ajax({
            url: '../php/carrinho.php',
            method: 'POST',
            dataType: 'html',
            success: function(response) {
                $('#tbody_carrinho').html(data)
            },
            error: function(xhr, status, error) {
                console.error('Erro ao carregar o carrinho: ', error)
                console.error('Erro xhr: ', xhr.responseText)
                console.error('Status: ', status)
            }
        })
    }*/
})