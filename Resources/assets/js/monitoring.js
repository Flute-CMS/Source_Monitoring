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
        toast({
            type: 'error',
            message: translate('monitoring.copy_ip.error')
        });
    }
}

function showInfoModal(serverId) {
    $('.modal-info').addClass('opened');
    document.body.style.overflow = 'hidden';
    loadingInfo(true);
    updateInfoModalData(serverId, false);
}

function loadingInfo(state) {
    //TODO Skeleton modal loading
    console.log(state);
}

function updateInfoModalData(serverId, force) {
    $('#server_refresh').prop('disabled', true);
    $.ajax({
        url: u('source_monitoring/api/info?server_id=' + serverId + "&force=" + force),
        type: 'GET',
        success: function (response) {
            loadingInfo(false);
            
            $('#img_bg_modal').attr('src', u(response.info.Map_img));
            $('#server_refresh').prop('disabled', false).off('click').click(function() {
                updateInfoModalData(response.id, true);
            });
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