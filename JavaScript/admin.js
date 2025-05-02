$(document).ready(function() {
    //remover usuário e produtos
    $('.remover_usuario').on('click', function() {
        let user_id = $(this).data('id');
        const confirmar = confirm('Tem certeza que deseja remover este usuário?');
        if(!confirmar) {
            console.log('Usuário não removido.');
            return
        }
        console.log('Enviando:', {
            action: 'remover_usuario',
            id: user_id
        });
        $.ajax({
            url: 'process_admin.php',
            type: 'POST',
            data: { action: 'remover_usuario', id: user_id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                    console.log('Usuário removido com sucesso.');
                } else {
                    console.log(response);
                    alert('Erro ao remover usuário: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Erro ao conectar ao servidor:', error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                alert('Erro ao conectar ao servidor. Tente novamente mais tarde.');
            }
        });
    })
    //editar usuário
    $('.editar_usuario').on('click', function() {
        let user_id = $(this).data('id');
        let novoTipo = prompt('Digite o novo tipo de usuário (admin ou user):');

        // Verifica se foi digitado algo e se é um valor válido
        if (!novoTipo || (novoTipo !== 'admin' && novoTipo !== 'user')) {
            alert('Tipo inválido! Digite apenas "admin" ou "user".');
            return;
        }
        
        $.ajax({
            url: 'process_admin.php',
            type: 'POST',
            data: {action: 'editar_usuario', id: user_id, newType: novoTipo},
            dataType: 'json',
            success: function(response) {
                if(novoTipo) {
                    if(response.success) {
                        location.reload();
                        console.log('Usuário editado com sucesso.');
                    } else {
                        console.log(response)
                        alert('Erro ao editar usuário: ' + response.message);
                    }
                }
            },
            error:(function(xhr, status, error) {
                console.log('ERRO: ' + error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                alert('Erro ao conectar ao servidor. Tente novamente mais tarde.');
            })
        })
    })

    //adicionar usuário
    $('#form_add_users').on('submit', function(event) {
        event.preventDefault();
        
        //validando campos
        let username = $('#username').val();
        let email = $('#email').val();
        let password = $('#password').val();
        let image = $('#image').val();
        let errorMessage = '';

        // verifica se o email é válido
        let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if (!emailPattern.test(email)) {
            errorMessage = 'Por favor, inserir um e-mail valido.'
        } 

        //validação da senha
        const passwordRegex = /[^a-zA-Z0-9]/
        if(password.length < 8 || !passwordRegex.test(password)) {
            errorMessage = 'Senha precisa ter pelo menos 8 letras e um caractere especial.'
        }

        //validando imagem
        if(!image) {
            errorMessage = 'Por favor, escolha uma imagem.'
        }

        if(errorMessage) {
            $('#error-message').text(errorMessage)
            return;
        }

        var formData = new FormData(this);
        $.ajax({
            url: 'users.php',
            type:'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                response = JSON.parse(response)
                if(response.success) {
                    window.location.reload();
                } else {
                    $('#error-message').text(response.message)
                }
            },
            error: function(xhr, status, error) {
                console.log('ERRO: ' + error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                $('#error-message').text('Erro ao processar formulário.')
            }
        })

    })

    //adicionar produto
    $('#form_add_products').on('submit', function(event) {
        event.preventDefault();

        let image = $('#image').val()
        let errorMessage = ''

        //validando imagem
        if(!image) {
            errorMessage = 'Por favor, escolha uma imagem.'
        }

        var formData = new FormData(this);
        $.ajax({
            url: 'products.php',
            type:'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                response = JSON.parse(response)
                if(response.success) {
                    window.location.reload();
                } else {
                    $('#error-message').text(response.message)
                }
            },
            error: function(xhr, status, error) {
                console.log('ERRO: ' + error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                $('#error-message').text('Erro ao processar formulário.')
            }
        })
        
        
    })

    //remover produto
    $('.remover_produto').on('click', function() {
        let product_id = $(this).data('id');
        const confirmar = confirm('Tem certeza que deseja remover este produto?');
        if(confirmar) {
            console.log('Produto removido com sucesso.');
        } else {
            console.log('Produto não removido.');
            return;
        }
        console.log('Enviando:', {
            action: 'remover_produto',
            id: product_id
        });
        $.ajax({
            url: 'process_admin.php',
            type: 'POST',
            data: { action: 'remover_produto', id: product_id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    location.reload();
                    console.log('Produto removido com sucesso.');
                } else {
                    console.log(response);
                    alert('Erro ao remover produto: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.log('Erro ao conectar ao servidor:', error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                alert('Erro ao conectar ao servidor. Tente novamente mais tarde.');
            }
        });
    })

    //editar produtos
    $('#edit_form').on('submit', function(event) {
        event.preventDefault()
        console.log('botão funciona')
        const codigo = $('#code').val()


        $.post('editar_produtos.php', {code: codigo}, function(response) {
            if(response.success) {
                location.reload()
                $('#resultado').html(`<pre> ${response.resultado} </pre>`)
            } else {
                $('#resultado').text(`<pre> ${response.message} </pre>`)
            }
        }, 'json')
    })

    /*
    $('.editar_produto').on('click', function() {
        let product_id = $(this).data('id');
        let name = prompt('Edite o name do produto:');
        let description = prompt('Edite descrição do produto do produto:');
        let price = prompt('Edite o valor do produto:');
        let stock = prompt('Edite o stock do produto:');
        let type = prompt('Edite o tipo do produto:');

        // Verifica se foi digitado algo e se é um valor válido
        if (!name || !description || !price || !stock || !type) {
            alert('Pelo menos um valor precisa ser editado');
            return;
        }
        
        $.ajax({
            url: 'editar_produtos.php',
            type: 'POST',
            data: {action: 'editar_produto', 
                id: product_id, 
                name: name,
                description: description,
                price: price,
                stock: stock,
                type: type,
            },
            dataType: 'json',
            success: function(response) {
                    if(response.success) {
                        location.reload();
                        console.log('Produto editado com sucesso.');
                    } else {
                        console.log(response)
                        alert('Erro ao editar produto: ' + response.message);
                    }
            },
            error:(function(xhr, status, error) {
                console.log('ERRO: ' + error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                alert('Erro ao conectar ao servidor. Tente novamente mais tarde.');
            })
        })
    })    





    /*
    let sent = false
    $('.editar_produto').on('click', function() {
        let product_id = $(this).data('id')
        const confirmar = confirm(`Tem certeza de que deseja editar produto de ID: ${product_id}`);
        if (confirmar) {
            console.log(product_id)
            sent = true;
        }

        $.ajax({
            url: 'editar_produtos.php',
            type: 'POST',
            data: {
                action: 'pegar_produto',
                product_id: product_id
            },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response.success) {
                    console.log('Produto enviado ao servidor.');
                    // Preenchendo os campos do formulário com os dados do produto
                    $('#name').val(response.product.name);
                    $('#description').val(response.product.description);
                    $('#price').val(response.product.price);
                    $('#stock').val(response.product.stock);
                    $('#type').val(response.product.type);
                } else {
                    alert('Produto não encontrado ou erro no servidor.');
                }
            },
            error: function(xhr, status, error) {
                console.log('ERRO: ' + error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                alert('Erro ao conectar ao servidor. editar_produto');
            }
        });
    });
    $('#form_edit').on('submit', function(event) {
        event.preventDefault();
        if (!sent) {
            event.preventDefault();
            alert('Precisa selecionar qual produto quer editar!')
            return;
        }

        var formData = new FormData(this);
        formData.append('action', 'editar_produto');
        console.log("Dados enviados:", formData);
        
        $.ajax({
            url: 'editar_produtos.php',
            type:'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log('Resposta do servidor:', response);
                if (response.success) {
                    console.log('Produto editado com sucesso.');
                    window.location.reload();
                } else {
                    alert(response.message || 'Erro ao editar produto');
                }
            },
            error: function(xhr, status, error) {
                console.log('ERRO: ' + error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                $('#error-message').text('Erro ao processar formulário.')
            }
        })
    })*/
})

/*
let sent = false
    $('.editar_produto').on('click', function(event) {
        event.preventDefault();
        const confirmar = confirm("Tem certeza que deseja editar este produto?");
        if (confirmar) {
            sent = true;
            $('#form_edit_product').submit();
        }
    });
    $('#form_edit_product').on('submit', function(event) {
        if (!sent) {
            event.preventDefault();
            return;
        }
        event.preventDefault();
    
        const formData = new FormData(this);
        formData.append('action', 'editar_produto');
    
        $.ajax({
            url: 'process_admin.php',
            type: 'POST',
            data: {action: 'editar_produto'},
            contentType: false,
            processData: false,
            success: function(response) {
                try {
                    response = JSON.parse(response);
                    if (response.success) {
                        window.location.reload();
                    } else {
                        $('#error-message').text(response.message);
                    }
                } catch (err) {
                    $('#error-message').text("Erro na resposta.");
                    console.error("Erro ao passar resposta:", err);
                }
            },
            error: function(xhr, status, error) {
                console.log('ERRO: ' + error);
                console.log('ERRO xhr: ' + xhr.responseText);
                console.log('STATUS: ' + status);
                $('#error-message').text('Erro ao processar formulário.');
            }
        });
    
        sent = false;
    });

*/

