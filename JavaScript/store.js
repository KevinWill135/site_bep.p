document.getElementById('filtros').addEventListener('change', filtrar)

class Produto {
    constructor(elemento) {
        this.elemento = elemento
        this.tipo = elemento.dataset.tipo
        this.preco = parseFloat(elemento.getElementsByClassName('precos')[0].innerText.replace('€', '').trim())
    }

    //verificando se o produto atende ao filtro
    atendeTipo(tipoSel) {
        return !tipoSel || this.tipo === tipoSel
    }

    //verificando se o produto atende ao filtro
    atendePreco(precosSel) {
        if(precosSel.length === 0) return true

        return precosSel.some(faixa => {
            if(faixa === 'preco1' && this.preco <= 10) return true
            if(faixa === 'preco2' && this.preco > 10 && this.preco <= 25) return true
            if(faixa === 'preco3' && this.preco > 25 && this.preco <= 50) return true
            return false
        })
    }

    //aplicando visibilidade para o produto
    aplicarVisibility(exibir) {
        this.elemento.style.display = exibir ? 'flex' : 'none'
    }
}

class Filtro {
    constructor(produtos, tipoSel, precosSel) {
        this.produtos = produtos.map(produto => new Produto(produto))
        this.tipoSel = tipoSel
        this.precosSel = precosSel
    }

    //aplicar filtro e retornar a quantidade de produtos visíveis
    aplicarFiltro() {
        let visibility = 0

        this.produtos.forEach(produto => {
            const atendeFiltro = produto.atendeTipo(this.tipoSel) && produto.atendePreco(this.precosSel)
            if(atendeFiltro) visibility++
            produto.aplicarVisibility(atendeFiltro)
        });
        console.log(this.precosSel)
        //caso não tenha produtos visíveis
        /*
        if(visibility === 0) {
            if(this.precosSel[0] === 'preco1' || this.precosSel[0] === 'preco2' || this.precosSel[0] === 'preco1') {
                this.precosSel = 'Não há produto nesse valor, tente mudar o filtro'
            }
            showAlert(`
                <h3>Nenhum produto encontrado para esse filtro.</h3> 
                <p>Tente mudar o filtro de tipo: ${this.tipoSel}</p>
                <p>${this.precosSel}</p>
                `)
            const div = document.querySelectorAll('#produtos-container .produto')
            div.forEach(item => {
                item.style.display = 'flex'
            })
        }*/
    }
}

//função de filtragem chamada ao aplicar os filtros
function filtrar() {
    const produtos = Array.from(document.querySelectorAll('#produtos-container .produto'))
    const tipoSel = document.getElementById('tipo').value
    const precosSel = Array.from(document.querySelectorAll('#precos input:checked')).map(input => input.id)
    //console.log(produtos)
    //console.log(tipoSel)
    //console.log(precosSel)

    //criando a instância do filtro
    const filtro = new Filtro(produtos, tipoSel, precosSel)
    filtro.aplicarFiltro()
}

//função que irá chamar o ALERTA
function showAlert(mensagem) {
    const alerta = document.getElementById('alerta-filtro')
    alerta.innerHTML = mensagem
    alerta.classList.remove('d-none')
    alerta.style.position = 'absolute'
    alerta.style.zIndex = '1000'

    //escondendo automaticamente
    setTimeout(() => {
        alerta.classList.add('d-none')
    }, 4000)
}

//resetar filtros
function reset() {
    const typeSelect = document.getElementById('tipo')
    const checkboxes = document.querySelectorAll('#precos input')

    typeSelect.value = ''
    typeSelect.dispatchEvent(new Event('change'))

    checkboxes.forEach(checkbox => {
        checkbox.checked = false
        checkbox.dispatchEvent(new Event('change'))
    })
}