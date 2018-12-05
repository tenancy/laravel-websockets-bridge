<?php
namespace Tenancy\LaravelWebsockets\Models;

use BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry as BaseWebsocketsStatisticsEntry;
use Hyn\Tenancy\Traits\UsesSystemConnection;

class WebsocketsStatisticsEntry extends BaseWebSocketsStatisticsEntry
{
    use UsesSystemConnection;
}