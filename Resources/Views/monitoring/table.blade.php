<div class="monitoring_table">
    <h3 class="mb-2">@t('monitoring.our_servers')</h3>

    <div class="monitoring_overflow">

        <table class="monitoring_table">
            <thead>
                <tr>
                    <th></th>
                    <th>@t('monitoring.server_name')</th>
                    <th>@t('monitoring.map')</th>
                    <th>IP:PORT</th>
                    <th class="center">@t('monitoring.players')</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($servers as $item)
                    <tr>
                        <td><img src="@asset($item['info']['Map_img'])" alt="{{ $item['info']['HostName'] }}"></td>
                        <td>{{ $item['info']['HostName'] }}</td>
                        <td>
                            <div class="map_pin">
                                <img src="@asset($item['info']['Map_pin'])">
                                {{ $item['info']['Map'] }}
                            </div>
                        </td>
                        <td class="monitoring_ip_port" onclick="copyIpToClipboard('{{ $item['id'] }}')" data-tooltip="@t('monitoring.copy_ip.description')" data-tooltip-conf="right">{{ $item['ip'] }}:{{ $item['port'] }}</td>
                        <td class="monitoring_players" onclick="showInfoModal('{{ $item['id'] }}')" data-tooltip="@t('monitoring.open_info')" data-tooltip-conf="left">
                            {{ $item['info']['Players'] }}/{{ $item['info']['MaxPlayers'] }}
                            <div class="progress">
                                <div style="width: {{ $item['info']['percentOnline']['percent'] }}%"
                                    class="{{ $item['info']['percentOnline']['name'] }}"></div>
                            </div>
                        </td>
                        <td>
                            <a class="btn btn--with-icon size-s outline"
                                href="steam://connect/{{ $item['ip'] }}:{{ $item['port'] }}">
                                @t('monitoring.connect')
                                <span class="btn__icon arrow"><i class="ph ph-arrow-up-right"></i></span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @component(mm('Source_Monitoring', 'Resources/Views/components/info.blade.php'))
    @endcomponent
</div>

