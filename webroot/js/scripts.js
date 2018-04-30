$.fn.extend({
    bootstrapAlert: function(alertType, message)
    {
        var $alert, $content;
        $alert = $('<div></div>', {
            class: 'alert alert-dismissable',
            role: 'alert',
            html: [
                $('<button></button>', {
                    type: 'button',
                    'data-dismiss': 'alert',
                    class: 'close',
                    'aria-label': 'Close',
                    html: $('<i></i>', {class: 'fas fa-times'})
                }),
                $('<div></div>', {
                    class: 'message-content',
                    html: [$('<i></i>', {class: 'fas'}), $('<span></span>')]
                })
            ]
        });
        $content = $alert.find('.message-content');
        $content.find('span').text(' ' + message);

        switch (alertType) {
            case 'success':
                $alert.addClass('alert-success');
                $content.find('i').addClass('fa-check-circle');
                break;
            case 'error':
                $alert.addClass('alert-danger');
                $content.find('i').addClass('fa-exclamation-circle');
                break;
            case 'info':
                $alert.addClass('alert-info');
                $content.find('i').addClass('fa-info-circle');
                break;
            case 'warning':
                $alert.addClass('alert-warning');
                $content.find('i').addClass('fa-exclamation-triangle');
                break;
        }

        $(this).find('.alert').remove();
        $(this).append($alert); 
    }
});

$(document).ready(function(){
    function baixaArquivo(dados) {
        if (dados.quantidade && dados.sequenciais && dados.arquivo) {
            if (dados.arquivo.nome && dados.arquivo.tipo) {
                $.ajax({
                    url: '/Nfce/download',
                    xhrFields: { responseType: 'blob' },
                    data: { 
                        qtd: dados.quantidade, 
                        seqs: dados.sequenciais 
                    },
                    method: 'POST'
                })
                .done(function(data, status) {
                    var url = window.URL.createObjectURL(new Blob([data], {
                        type: dados.arquivo.tipo
                    }));
                    var $a = document.createElement('a');
                    document.body.appendChild($a);

                    $a.download = dados.arquivo.nome;
                    $a.style = 'display: none';
                    $a.href = url;
                    $a.click();

                    window.URL.revokeObjectURL(url);
                });
            }
        }
    }

    var sequenciais = [];

    function filaDownload(acao, sequencial) {
        if (acao === 'adicionar') {
            if (sequenciais.indexOf(sequencial) === -1) {
                sequenciais.push(sequencial);
            }
        }
        else if (acao === 'remover') {
            if (sequenciais.indexOf(sequencial) > -1) {
                sequenciais.splice(sequenciais.indexOf(sequencial), 1);
            }
        }
    }

    $('.cnpj').mask('00.000.000/0000-00', {
        clearIfNotMatch: true,
        reverse: true,
        optional: false,
        translation: { '0': { pattern: /[0-9]/ } }
    });

    $.datetimepicker.setLocale('pt-BR');
    $('input.date').datetimepicker({
        timepicker: false,
        format: 'd/m/Y',
        mask: true
    });

    $('#mark-all').on('click', function() {
        $('.card-select').each(function() {
            $(this).prop('checked', true).change();
        });
    });

    $('#unmark-all').on('click', function() {
        $('.card-select').each(function() {
            $(this).prop('checked', false).change();
        });
    });

    $('#find').on('click', function() {
        var $dataInicio = $('#inicio');
        var $dataFim = $('#fim');

        if ($dataInicio.val() != '' && $dataFim.val() != '') {
            var inicio =  $dataInicio.val().split('/').reverse().join('-');
            var fim =  $dataFim.val().split('/').reverse().join('-');

            $(location).attr(
                'href', '/Nfce/index/filtro/1/' + inicio + '/' + fim
            );
        }
    });

    $('.download-xml').on('click', function() {
        var nomeArquivo = $(this).attr('id');
        var sequenciais = [$(this).val()];

        baixaArquivo({
            quantidade: 1,  
            sequenciais: sequenciais,
            arquivo: {
                nome: nomeArquivo,
                tipo: 'text/html'
            }
        });
    });

    $('.card-select').on('change', function() {
        if (!$(this).prop('checked')) {
            filaDownload('remover', $(this).val());
        }
        else {
            filaDownload('adicionar', $(this).val());
        }

        if ($('.card-select:checked').length > 1) {
            $('#download-zip').off('click').on('click', function() {
                baixaArquivo({
                    quantidade: sequenciais.length,  
                    sequenciais: sequenciais,
                    arquivo: {
                        nome: 'NFCE.zip',
                        tipo: 'application/zip'
                    }
                });
            })
            .prop('disabled', false);
        }
        else {
            $('#download-zip').off('click').prop('disabled', true);
        }
    });
});