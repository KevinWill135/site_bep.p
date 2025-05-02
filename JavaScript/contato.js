function validar() {
    if ((contacto.nome.value == '')||(contacto.email.value == '')||(contacto.apelido.value == '')|| contacto.telefone.value == ''){
        alert('Há mais de um campo vazio, favor preencher.')
    } else {
        alert('Todos os campos preenchidos corretamente, formulário será enviado, em breve entraremos em contacto.')
    }
}