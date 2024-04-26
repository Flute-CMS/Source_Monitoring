<?php

namespace Flute\Modules\Source_Monitoring\src\Services;

use Flute\Core\Database\Entities\Server;
use xPaw\SourceQuery\SourceQuery;

class ServersMonitorService
{
    protected const CACHE_KEY = 'flute.monitoring.servers';
    protected const CACHE_PERFORMANCE_TIME = 3600;
    protected const CACHE_DEFAULT_TIME = 180;
    protected const CACHE_PERFORMANCE_TIME_DETAIL = 60;
    protected const CACHE_DEFAULT_TIME_DETAIL = 30;

    public function monitor(array $servers)
    {
        if (cache(self::CACHE_KEY))
            return cache(self::CACHE_KEY);

        $queries = [];

        foreach ($servers as $server) {
            $queries[] = $this->getInfo($server, 0);
        }

        cache()->set(self::CACHE_KEY, $queries, is_performance() ? self::CACHE_PERFORMANCE_TIME : self::CACHE_DEFAULT_TIME);

        return $queries;
    }

    public function monitorInfo($server, $force)
    {
        $cacheKey = self::CACHE_KEY . '.' . $server->id;
        $cacheTime = is_performance() ? self::CACHE_PERFORMANCE_TIME_DETAIL : self::CACHE_DEFAULT_TIME_DETAIL;
        $lastQueryTimeKey = $cacheKey . '.last_query_time';

        $lastQueryTime = cache()->get($lastQueryTimeKey, 0);
        $currentTime = time();

        if ($force && ($currentTime - $lastQueryTime >= 10)) {
            $result = $this->getInfo($server, 0);
            cache()->set($cacheKey, $result, $cacheTime);
            cache()->set($lastQueryTimeKey, $currentTime, $cacheTime);
        } else {
            $result = cache()->get($cacheKey);
            if (!$result) {
                $result = $this->getInfo($server, 0);
                cache()->set($cacheKey, $result, $cacheTime);
                cache()->set($lastQueryTimeKey, $currentTime, $cacheTime);
            }
        }

        return $result;
    }

    protected function getInfo($server, $tryCount)
    {
        $query = null;
        $serverResult = [];
    
        try {
            $query = new SourceQuery;
            $query->Connect($server->ip, $server->port);
            $serverResult = $this->processServerQuery($server, $query);
        } catch (\Exception $e) {
            if ($tryCount > -1) {
                $serverResult = $this->noConnectServer($server);
            } else {
                return $this->getInfo($server, $tryCount + 1);
            }
        } finally {
            if ($query !== null) {
                $query->Disconnect();
            }
        }
    
        $serverResult['id'] = $server->id;
    
        return $serverResult;
    }
    

    protected function noConnectServer($server) 
    {
        $serverResult = [
            'id' => $server->id,
            'ip' => $server->ip,
            'port' => $server->port,
        ];

        $serverResult['info']['percentOnline'] = $this->getPercentName(0, 1);
        $serverResult['info']['Players'] = '-';
        $serverResult['info']['MaxPlayers'] = '-';

        $serverResult['info']['Map'] = 'monitoring.no_map';
        $serverResult['info']['Map_img'] = $this->getMapImg($server->mod, '-');
        $serverResult['info']['Map_pin'] = $this->getMapPin('_');
        $serverResult['info']['HostName'] = 'monitoring.info.server_is_shutdown';
        $serverResult['info']['HostName_replace'] = $server->name;

        return $serverResult;
    }

    protected function processServerQuery($server, $query)
    {
        $serverResult = [
            'ip' => $server->ip,
            'port' => $server->port,
            'info' => $query->GetInfo(),
            'players' => $query->GetPlayers(),
        ];

        $serverResult['info']['HostName_replace'] = $server->name;
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


    public function findServer($serverId) 
    {
        return rep(Server::class)->select()->where('id', '=', $serverId)->fetchAll();
    }
}
