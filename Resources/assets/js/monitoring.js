function showInfoModal(serverId) {
    $('.modal-info').addClass('opened');
    document.body.style.overflow = 'hidden';
    setLoadingState(true);
    updateInfoModalData(serverId, false);
}

function setLoadingState(isLoading) {
    if (isLoading) {
        $('#map_name').text('-----');
        $('#table-body-players').empty();
        for (let i = 0; i < 5; i++) {
            $('#table-body-players').append(
                '<div class="div-table-row skeleton"></div>',
            );
        }
    } else {
        $('.img-bg-container').removeClass('skeleton');
    }
}

function updateInfoModalData(serverId, force) {
    $.ajax({
        url: u(
            `source_monitoring/api/info?server_id=${serverId}&force=${force}`,
        ),
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            setLoadingState(false);

            const info = response.info;
            const serverName = response.serverName;
            const players = response.players;

            $('#img_bg_modal').attr('src', u(info.Map_img));
            $('#img_pin').attr('src', u(info.Map_pin));
            $('#map_name').text(info.Map);

            $('#server_refresh')
                .prop('disabled', false)
                .off('click')
                .on('click', function () {
                    updateInfoModalData(response.id, true);
                });

            $('#server_modal_name').text(serverName);
            $('#bt_copy_ip').attr(
                'data-copy',
                `${response.ip}:${response.port}`,
            );
            $('#bt_play').attr(
                'href',
                `steam://connect/${response.ip}:${response.port}`,
            );

            updatePlayerTable(players);
        },
        error: function (xhr) {
            const errorMessage =
                xhr?.responseJSON?.error || translate('def.unknown_error');
            toast({ message: errorMessage, type: 'error' });
            closeInfoModal();
        },
    });
}

function updatePlayerTable(players) {
    const tableBody = $('#table-body-players');
    tableBody.empty();

    if (players.length > 0) {
        players.forEach((player) => {
            const row = $('<div class="div-table-row"></div>');
            row.append(
                '<div class="div-table-cell"><i class="ph ph-link"></i></div>',
            );
            row.append(
                `<div class="div-table-cell"><p>${player.Name}</p></div>`,
            );
            row.append(
                `<div class="div-table-cell"><p>${player.Frags}</p></div>`,
            );
            row.append(
                `<div class="div-table-cell"><p>${player.TimeF}</p></div>`,
            );
            tableBody.append(row);
        });
    } else {
        tableBody.append(
            '<div class="div-table-row"><div class="div-table-cell" colspan="4">No players found</div></div>',
        );
    }
}

function closeInfoModal() {
    $('.modal-info').removeClass('opened');
    $('#img_bg_modal').attr('src', '');
    document.body.style.overflow = '';
    setLoadingState(false);
}

$(document).on('click', '.modal-info', function (event) {
    if ($(event.target).is('.modal-info')) {
        closeInfoModal();
    }
});
