<div class="monitoring_container">
    <h3 class="mb-2">@t('monitoring.our_servers')</h3>

    <div class="row gx-4 gy-4">
        @foreach ($servers as $item)
            <div class="col-md-4">
                <div class="monitoring_card" onclick="showInfoModal('{{ $item['id'] }}')">
                    <img src="@asset($item['info']['Map_img'])" alt="{{ $info['Map']['HostName'] }}">

                    <div class="monitoring_card-content">
                        <div>{{ $item['info']['HostName'] }}</div>
                        <div>
                            <img src="@asset($item['info']['Map_pin'])">
                            {{ $item['info']['Map'] }}
                        </div>
                    </div>

                    <a href="steam://connect/{{ $item['ip'] }}:{{ $item['port'] }}" class="monitoring_card-playbtn">
                        <i class="ph ph-play play"></i>
                        <i class="ph ph-arrow-up-right arrow"></i>
                    </a>

                    <div class="monitoring_card-footer">
                        <div class="monitoring_card-footer-text">
                            <p id="{{ $item['id'] }}" onclick="copyIpToClipboard('{{ $item['id'] }}')" data-tooltip="@t('monitoring.copy_ip.description')" data-tooltip-conf="top">
                                <i class="ph ph-copy"></i>
                                {{ $item['ip'] }}:{{ $item['port'] }}
                            </p>
                            <p>
                                <i class="ph ph-users"></i>
                                {{ $item['info']['Players'] }}/{{ $item['info']['MaxPlayers'] }}
                            </p>
                        </div>

                        <div class="monitoring_card-footer-progress">
                            <div style="width: {{ $item['info']['percentOnline']['percent'] }}%"
                                class="{{ $item['info']['percentOnline']['name'] }}"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@push('content')
    @component(mm('Source-Monitoring', 'Resources/Views/components/info.blade.php'))
    @endcomponent
@endpush