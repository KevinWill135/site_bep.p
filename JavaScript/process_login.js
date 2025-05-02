$('#login-form').submit(function(event) {
    event.preventDefault();

    let email = $('#email').val()
    let password = $('#password').val()
    let message = '';

    let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if(!emailRegex.test(email)) {
        message = 'Email inválido'
    }

    if(password.length < 7) {
        message = 'Senha insuficiente'
    }

    if(message) {
        $('#message').text(message)
        return
    }
    console.log(email)
    console.log(password)

    var formData = new FormData(this)
    $.ajax({
        url: '../php/process_login.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
            console.log(response)
            //convertendo a resposta JSON
            //response = JSON.parse(response)
            if(response.success) {
                window.location.href = response.redirect
            } else {
                $('#message').text(response.message)
            }
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição:', error);
            $('#message').text('Erro no servidor. Tente novamente mais tarde.');
        }
    })
})