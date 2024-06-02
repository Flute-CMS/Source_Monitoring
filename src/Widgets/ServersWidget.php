<?php

namespace Flute\Modules\Source_Monitoring\src\Widgets;

use Flute\Core\Database\Entities\Server;
use Flute\Core\Database\Entities\WidgetSettings;
use Flute\Core\Widgets\AbstractWidget;
use Flute\Modules\Source_Monitoring\src\Services\ServersMonitorService;
use Spiral\Database\Injection\Parameter;
use Nette\Utils\Html;

class ServersWidget extends AbstractWidget
{
    protected array $servers = [];
    protected const CACHE_KEY = 'flute.monitoring.servers';
    protected const SOURCE_GAME_CODE = [
        '730', // CS 2 / CS:GO
        '240', // CS:S
        '10',  // Counter-Strike 1.6
        '440', // Team Fortress 2
        '550', // Left 4 Dead 2
        '1002', // Rag Doll Kung Fu
        '2400', // The Ship
        '4000', // Garry's Mod
        '17710', // Nuclear Dawn
        '70000', // Dino D-Day
        '107410', // Arma 3
        '115300', // Call of Duty: Modern Warfare 3
        '162107', // DeadPoly
        '211820', // Starbound
        '244850', // Space Engineers
        '304930', // Unturned
        '251570', // 7 Days to Die
        '252490', // Rust
        '282440', // Quake Live
        '346110', // ARK: Survival Evolved
        '108600', // Project: Zomboid
        'all_hl_games_mods' // HL1 / HL2 Game
    ];
    protected ServersMonitorService $monitorService;

    public function __construct()
    {
        $this->monitorService = app(ServersMonitorService::class);

        $this->setAssets([
            mm('Source_Monitoring', 'Resources/assets/js/monitoring.js'),
            mm('Source_Monitoring', 'Resources/assets/scss/monitoring.scss'),
        ]);

        $this->getServers();

        if (!$this->servers)
            return;

        $this->setReloadTime(60000);
    }

    public function render(array $data = []): string
    {
        if (!$this->servers)
            return '';

        $type = isset($data['type']) && $data['type'] === 'table' ? 'table' : 'default';

        return render(mm('Source_Monitoring', "Resources/Views/monitoring/{$type}"), [
            'servers' => $this->monitorService->monitor($this->servers)
        ]);
    }

    public function placeholder(array $settingValues = []): string
    {
        $row = Html::el('div');
        $row->addClass('row gx-4 gy-4');

        $col = Html::el('div');

        $placeHolder = Html::el('div');
        $placeHolder->addClass('skeleton');

        if ($settingValues['type'] === 'table') {
            $col->addClass('col-md-12');
            $placeHolder->style('height', '300px');
        } else {
            $col->addClass('col-md-4');
            $row->addHtml($col);
            $row->addHtml($col);
            $placeHolder->style('height', '200px');
        }

        $col->addHtml($placeHolder);

        $row->addHtml($col);

        return $row->toHtml();
    }

    public function getName(): string
    {
        return 'Monitoring SOURCE widget';
    }

    public function isLazyLoad(): bool
    {
        return true;
    }

    public function getDefaultSettings(): array
    {
        $setting = new WidgetSettings;
        $setting->name = 'type';
        $setting->description = 'monitoring.widgets.description';
        $setting->type = 'select';
        $setting->setValue([
            'items' => [
                'default',
                'table'
            ]
        ]);

        return [
            'type' => $setting
        ];
    }

    protected function getServers(): void
    {
        $this->servers = rep(Server::class)->select()->where('mod', 'in', new Parameter(self::SOURCE_GAME_CODE))->where('enabled', true)->fetchAll();
    }
}