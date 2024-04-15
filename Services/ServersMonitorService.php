<?php

namespace Flute\Modules\Monitoring\Services;

use Flute\Core\Database\Entities\Server;
use xPaw\SourceQuery\SourceQuery;

class ServersMonitorService
{
    protected const CACHE_KEY = 'flute.monitoring.servers';
    protected const CACHE_PERFORMANCE_TIME = 3600;
    protected const CACHE_DEFAULT_TIME = 60;

    public function getServers(): array
    {
        return rep(Server::class)->findAll();
    }

    public function monitor(array $servers)
    {
        if (cache(self::CACHE_KEY))
            return cache(self::CACHE_KEY);

        $queries = [];

        foreach ($servers as $index => $server) {
            try {
                $query = new SourceQuery;
                $query->Connect($server->ip, $server->port);

                $serverResult = $this->processServerQuery($server, $query);
                $serverResult['id'] = $index;
                $queries[] = $serverResult;

            } catch (\Exception $e) {
                //TODO server not working - add placeholder
                // skip
            } finally {
                $query->Disconnect();
            }
        }

        cache()->set(self::CACHE_KEY, $queries, is_performance() ? self::CACHE_PERFORMANCE_TIME : self::CACHE_DEFAULT_TIME);

        dd($queries);

        return $queries;
    }

    protected function processServerQuery($server, $query)
    {
        $serverResult = [
            'ip' => $server->ip,
            'port' => $server->port,
            'info' => $query->GetInfo(),
            'players' => $query->GetPlayers(),
        ];

        $serverResult['info']['percentOnline'] = $this->getPercentName($serverResult['info']['Players'], $serverResult['info']['MaxPlayers']);

        if (isset($serverResult['info']['Map'])) {
            $serverResult['info']['Map_img'] = $this->getMapImg($server->mod, $serverResult['info']['Map']);
            $serverResult['info']['Map_pin'] = $this->getMapPin($serverResult['info']['Map']);
        }

        return $serverResult;
    }

    protected function getPercentName(int $players, int $maxPlayers)
    {
        $percent = ((int) $players / $maxPlayers) * 100;

        $name = 'green';
        
        if( $percent > 80 ) $name = 'error';
        elseif( $percent > 40 ) $name = 'warning';

        return [
            'name' => $name,
            'percent' => $percent,
        ];
    }

    protected function getMapPin(string $map): string
    {
        $map = sprintf("%s/public/assets/img/pins/_%s.webp", BASE_PATH, $map);
        if (file_exists($map))
            return str_replace(BASE_PATH . '/public/', '', $map);

        return 'assets/img/pins/_.webp';
    }

    protected function getMapImg(string $mod, string $map): string
    {
        $map = sprintf("%s/public/assets/img/maps/%s/%s.webp", BASE_PATH, $mod, $map);
        if (file_exists($map))
            return str_replace(BASE_PATH . '/public/', '', $map);

        return sprintf('assets/img/maps/%s/-.webp', $mod);
    }
}
