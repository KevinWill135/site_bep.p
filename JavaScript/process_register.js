$(document).ready(function () {
    $('#register-form').submit(function (event) {
        //evitar o envio padrão do formulário
        event.preventDefault();

        //recuperar os dados do formulário
        let username = $('#username').val()
        let email = $('#email').val()
        let password = $('#password').val()
        let confirmPassword = $('#confirmPassword').val()
        let profilePic = $('#profilePic').val()
        let errorMessage = ''

        //validando nome de usuário
        if(username.length < 3) {
            errorMessage = 'Você precisa adicionar letras no username.'
        }

        //validando email
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        if(!emailRegex.test(email)) {
            errorMessage = 'Email inválido!'
        }

        //validando senhas
        const passwordRegex = /[^a-zA-Z0-9]/
        if(password.length < 8 || !passwordRegex.test(password)) {
            errorMessage = 'Senha precisa ter pelo menos 7 letras e um caractere especial.'
        }

        if(password !== confirmPassword) {
            errorMessage = 'As senhas não conferem!'
        }

        //validando imagem
        if(!profilePic) {
            errorMessage = 'Por favor, escolha uma imagem.'
        }

        if(errorMessage) {
            $('#error-message').text(errorMessage)
            return;
        }

        //enviando formulário para o backend
        var formData = new FormData(this)
        $.ajax({
            url: '../php/process_register.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                console.log(response)
                //convertendo a resposta JSON
                response = JSON.parse(response)

                if(response.success) {
                    window.location.href = 'login.php'
                } else {
                    $('#error-message').text(response.message)
                }
            },
            error: function() {
                $('#error-message').text('Erro ao processar formulário.')
            }
        })
    })
})