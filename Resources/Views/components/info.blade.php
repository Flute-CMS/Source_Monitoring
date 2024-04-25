<div class="modal-info">
    <div class="modal-info-block">
        <div class="img-bg-container">
            <img id="img_bg_modal"/>
        </div>
        <div class="modal-info-block-header">
            <p>@t('monitoring.players')</p>
            <i id="server_refresh" class="ph ph-arrow-clockwise"></i>
            <div class="map-pin">
                <img id="img_pin" src="https://armatura-csgo.com/assets/img/pins/_de_dust2.webp">
                <p id="map_name">de_dust2</p>
            </div>
            <i class="ph ph-x" onclick="closeInfoModal()"></i>
        </div>
        <div class="modal-info-block-content">
            <div class="div-table">
                <div class="div-table-header">
                    <div class="div-table-row">
                        <div class="div-table-cell"><i class="ph ph-user"></i></i></div>
                        <div class="div-table-cell"><p>@t('monitoring.player')</p></div>
                        <div class="div-table-cell"><i class="ph ph-medal-military"></i></div>
                        <div class="div-table-cell"><i class="ph ph-timer"></i></div>
                    </div>
                </div>
                <div id="table-body-players">
                    
                </div>
            </div>
        </div>
        <div class="modal-info-block-footer">
            <button id="bt_copy_ip" class="copy-ip">@t('monitoring.copy_ip.description')</button>
            <a id="bt_play" href="" class="play-btn">@t('monitoring.play')</a>
        </div>
    </div>
</div>