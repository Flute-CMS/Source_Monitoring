<?php

namespace Flute\Modules\Source_Monitoring\src\Services;

use Flute\Core\Database\Entities\Server;
use xPaw\SourceQuery\SourceQuery;
use GuzzleHttp\Client;

class ServersMonitorService
{
    protected const CACHE_KEY = 'flute.monitoring.servers.';
    protected const CACHE_DEFAULT_TIME = 300;
    protected const CACHE_PERFORMANCE_TIME = 600;
    protected const MONITORING_INFO_URL = 'http://ip:port/monitoring-info';

    public function monitor(array $servers)
    {
        $queries = [];

        foreach ($servers as $server) {
            $queries[] = $this->getInfo($server);
        }

        return $queries;
    }

    public function getInfo(Server $server, $wasTried = false)
    {
        $cacheKey = self::CACHE_KEY . $server->id;

        if (cache()->has($cacheKey))
            return cache()->get($cacheKey);

        if ($server->enabled === false) {
            return $this->noConnectServer($server);
        }

        // $serverResult = $this->fetchMonitoringInfo($server);

        // if (!$serverResult) {
        $serverResult = $this->fetchServerQuery($server, $wasTried);
        // }

        $serverResult['id'] = $server->id;

        cache()->set($cacheKey, $serverResult, is_performance() ? self::CACHE_PERFORMANCE_TIME : self::CACHE_DEFAULT_TIME);

        return $serverResult;
    }

    protected function fetchMonitoringInfo(Server $server)
    {
        $client = new Client(['timeout' => 6, 'connect_timeout' => 6]);
        $url = str_replace(['ip', 'port'], [$server->ip, $server->port], self::MONITORING_INFO_URL);

        try {
            $response = $client->get($url);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);

                if ($data) {
                    return [
                        'ip' => $server->ip,
                        'port' => $server->port,
                        'info' => $data,
                        'serverName' => $server->name,
                        'displayIp' => $server->display_ip,
                        'status' => 'online',
                        // 'info' => [
                        //     'HostName_replace' => $server->name,
                        //     'percentOnline' => $this->getPercentName($data['info']['Players'], $data['info']['MaxPlayers']),
                        //     'Map_img' => $this->getMapImg($server->mod, $data['info']['Map']),
                        //     'Map_pin' => $this->getMapPin($data['info']['Map']),
                        // ]
                    ];
                }
            }
        } catch (\Exception $e) {
            // Log the exception if needed
        }

        return null;
    }

    protected function fetchServerQuery(Server $server, $wasTried)
    {
        $query = null;
        $serverResult = [];

        try {
            $query = new SourceQuery;
            $query->Connect($server->ip, $server->port, 3, ((int) $server->mod === 10) ? SourceQuery::GOLDSOURCE : SourceQuery::SOURCE);
            $serverResult = $this->processServerQuery($server, $query);
        } catch (\Exception $e) {
            if ($wasTried) {
                $serverResult = $this->noConnectServer($server);
            } else {
                return $this->getInfo($server, true);
            }
        } finally {
            if ($query !== null) {
                $query->Disconnect();
            }
        }

        return $serverResult;
    }

    protected function noConnectServer(Server $server)
    {
        $serverResult = [
            'id' => $server->id,
            'ip' => $server->ip,
            'port' => $server->port,
            'serverName' => $server->name,
            'displayIp' => $server->display_ip,
            'status' => 'offline'
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

    protected function processServerQuery(Server $server, $query)
    {
        $serverResult = [
            'ip' => $server->ip,
            'port' => $server->port,
            'info' => $query->GetInfo(),
            'players' => $query->GetPlayers(),
            'serverName' => $server->name,
            'displayIp' => $server->display_ip,
            'status' => 'online'
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

        if ($percent > 80)
            $name = 'error';
        elseif ($percent > 40)
            $name = 'warning';

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
