<?php

namespace Flute\Modules\Source_Monitoring\src\Widgets;

use Flute\Core\Database\Entities\Server;
use Flute\Core\Database\Entities\WidgetSettings;
use Flute\Core\Widgets\AbstractWidget;
use Flute\Modules\Source_Monitoring\src\Services\ServersMonitorService;
use Nette\Utils\Html;

class ServersWidget extends AbstractWidget
{
    protected array $servers = [];
    protected const CACHE_KEY = 'flute.monitoring.servers';
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
    }

    public function render(array $data = []): string
    {
        if (!$this->servers)
            return '';

        $type = isset ($data['type']) && $data['type'] === 'table' ? 'table' : 'default';

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
        //TODO load servers workong on SOURCE engine
        $this->servers = rep(Server::class)->findAll();
    }
}