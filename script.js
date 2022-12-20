$(document).ready(() => {
	

    $('#documentacao').on('click', function(){
        // $('#pagina').load('documentacao.html');

        $.get('documentacao.html', function(data){
            $('#pagina').html(data);
        })
    })

    $('#suporte').on('click', function(){
        $('#pagina').load('suporte.html');
    })


    //ajax

    $('#competencia').on('change', function(e){

        let competencia = $(e.target).val();

        $.ajax({
            type : 'GET',
            url : 'app.php',
            data : `competencia=${competencia}`,
            dataType : 'json',
            success: dados => {
                $('#numeroVendas').html(dados.numeroVendas)
                $('#totalVendas').html(dados.totalVendas)
                $('#clientesAtivos').html(dados.clientesAtivos)
                $('#clientesInativos').html(dados.clientesInativos)
                $('#totalReclamacoes').html(dados.totalReclamacoes);
                $('#totalElogios').html(dados.totalElogios);
                $('#totalSugestoes').html(dados.totalSugestoes);

                console.log(dados);
            },
            error: erro => {console.log(erro)}  
        })


        

        //método
        //url
        //dados - formulários
        //sucesso ou erro? que vamos fazer
    })
})