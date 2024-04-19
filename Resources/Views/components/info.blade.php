<div class="modal-info">
    <div class="modal-info-block">
        <div class="img-bg-container">
            <img id="img_bg_modal"/>
        </div>
        <div class="modal-info-block-header">
            <p>@t('monitoring.players')</p>
            <i id="server_refresh" class="ph ph-arrow-clockwise"></i>
            <i class="ph ph-x" onclick="closeInfoModal()"></i>
        </div>
        <div class="modal-info-block-content">
            <table>
                <thead>
                    <tr>
                        <td>
                            <i class="ph ph-user"></i>
                        </td>
                        <td>
                            <span>@t('monitoring.info.player')</span>
                        </td>
                        <td>@t('monitoring.info.score')</td>
                        <td>@t('monitoring.info.time')</td>
                    </tr>
                </thead>
                <tbody id="table-players">
                </tbody>
            </table>
        </div>
        <div class="modal-info-block-footer">
            Footer modal
        </div>
    </div>
</div>