<div class="modal-info">
    <div class="modal-info-block">
        <div class="img-bg-container">
            <img id="img_bg_modal"/>
        </div>
        <div class="modal-info-block-header">
            <h2>@t('monitoring.players')</h2>
            <div data-tooltip="@t('monitoring.info.refresh')" data-tooltip-conf="right">
                <i id="server_refresh" class="ph ph-arrow-clockwise"></i>
            </div>
            <div class="map-pin">
                <img id="img_pin">
                <p id="map_name">-----</p>
            </div>
            <div data-tooltip="@t('monitoring.info.close')" data-tooltip-conf="left">
                <i class="ph ph-x" onclick="closeInfoModal()"></i>
            </div>
        </div>
        <div class="modal-info-block-content">
            <div class="div-table">
                <div class="div-table-header">
                    <div class="div-table-row">
                        <div class="div-table-cell"><i class="ph ph-user"></i></i></div>
                        <div class="div-table-cell"><p>@t('monitoring.info.player')</p></div>
                        <div class="div-table-cell"><i class="ph ph-medal-military"></i></div>
                        <div class="div-table-cell"><i class="ph ph-timer"></i></div>
                    </div>
                </div>
                <div id="table-body-players">
                    
                </div>
            </div>
        </div>
        <div class="modal-info-block-footer">
            <a class="btn size-s outline info-action" id="bt_copy_ip">@t('monitoring.copy_ip.description')</a>
            <a class="btn size-s info-action" id="bt_play" href="">@t('monitoring.info.play')</a>
        </div>
    </div>
</div>