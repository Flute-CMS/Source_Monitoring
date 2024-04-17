<div class="modal-info">
    <div class="modal-info-block">
        <div class="img-bg-container">
            <img id="img_bg_modal"/>
        </div>
        <div class="modal-info-block-header">
            <span class="modal-title">@t('monitoring.players')</span>
            <i id="server_0" class="ph ph-arrow-clockwise" onclick="refreshInfoModal()"></i>
            <i class="ph ph-x" onclick="closeInfoModal()"></i>
        </div>
        <div class="modal-info-block-content">
            <table>
                <tr class="table-header">
                    <td>
                        <i class="ph-user"></i>
                        <span>@t('monitoring.info.player')</span>
                    </td>
                    <td>Knocky</td>
                    <td>Flor</td>
                    <td>Ella</td>
                    <td>Juan</td>
                </tr>
                <tr>
                    <td>Breed</td>
                    <td>Jack Russell</td>
                    <td>Poodle</td>
                    <td>Streetdog</td>
                    <td>Cocker Spaniel</td>
                </tr>
            </table>
        </div>
        <div class="modal-info-block-footer">
            Footer modal
        </div>
    </div>
</div>