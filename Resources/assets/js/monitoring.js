function copyIpToClipboard(elementId) {
    try {
        var textToCopy = document.getElementById(elementId);
        var temporaryElement = document.createElement('textarea');
        temporaryElement.style.position = 'absolute';
        temporaryElement.style.left = '-9999px';
        temporaryElement.setAttribute('readonly', '');
        temporaryElement.value = 'connect ' + (textToCopy.innerText || textToCopy.value);
        document.body.appendChild(temporaryElement);
        temporaryElement.select();
        document.execCommand('copy');
        document.body.removeChild(temporaryElement);
    
        toast({
            type: 'success',
            message: translate('monitoring.copy_ip.success')
        });
    } catch(error) {
        try {
            var temporaryElement = document.createElement('textarea');
            temporaryElement.style.position = 'absolute';
            temporaryElement.style.left = '-9999px';
            temporaryElement.setAttribute('readonly', '');
            temporaryElement.value = 'connect ' + elementId;
            document.body.appendChild(temporaryElement);
            temporaryElement.select();
            document.execCommand('copy');
            document.body.removeChild(temporaryElement);
            toast({
                type: 'success',
                message: translate('monitoring.copy_ip.success')
            });
        } catch(error) {
            toast({
                type: 'error',
                message: translate('monitoring.copy_ip.error')
            });
        }
    }
}

function showInfoModal(serverId) {
    $('.modal-info').addClass('opened');
    document.body.style.overflow = 'hidden';
    loadingInfo(true);
    updateInfoModalData(serverId, false);
}

function loadingInfo(state) {
    if(state) {
        $('#map_name').text('-----');
        $('#table-body-players').empty();
        for (let i = 0; i < 5; i++) {
            let newRow = $('<div class="div-table-row skeleton"></div>');
            $('#table-body-players').append(newRow);
        }
    } else {
        //TODO seleton to image bg
        $('.img-bg-container').removeClass('skeleton');
    }
}

function updateInfoModalData(serverId, force) {
    $.ajax({
        url: u('source_monitoring/api/info?server_id=' + serverId + "&force=" + force),
        type: 'GET',
        success: function (response) {

            console.log(response);

            loadingInfo(false);
            
            $('#img_bg_modal').attr('src', u(response.info.Map_img));
            $('#img_pin').attr('src', u(response.info.Map_pin));
            $('#map_name').text(response.info.Map);

            $('#server_refresh').prop('disabled', false).off('click').click(function() {
                updateInfoModalData(response.id, true);
            });

            $('#bt_copy_ip').on('click', function() {
                copyIpToClipboard(response.ip + ':' + response.port);
            });
            $('#bt_play').attr('href', 'steam://connect/' + response.ip + ':' + response.port);

            let players = response.players;

            // Очистка текущего содержимого таблицы перед добавлением новых данных
            $('#table-body-players').empty();

            if (players.length > 0) {
                players.forEach(player => {
                    let row = $('<div class="div-table-row"></div>');
                    row.append('<div class="div-table-cell"><i class="ph ph-link"></i></div>');
                    row.append('<div class="div-table-cell"><p>' + player.Name + '</p></div>');
                    row.append('<div class="div-table-cell"><p>' + player.Frags + '</p></div>');
                    row.append('<div class="div-table-cell"><p>' + player.TimeF + '</p></div>');

                    $('#table-body-players').append(row);
                });
            } else {
                $('#table-body-players').append('<div class="div-table-row"><div class="div-table-cell" colspan="4">No players found</div></div>');
            }
        },
        error: function (xhr, status, error) {

            let json = xhr?.responseJSON;

            toast({
                message: json?.error || translate('def.unknown_error'),
                type: 'error',
            });

            closeInfoModal();
        },
    });
}

function closeInfoModal() {
    $('.modal-info').removeClass('opened');
    $('#img_bg_modal').attr('src', null);
    document.body.style.overflow = '';
    loadingInfo(false);
}

$(document).on('click', '.modal-info', function(event) {
    if ($(event.target).is('.modal-info')) {
        closeInfoModal();
    }
});