<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\NotificationSenderPlugin\Action;

use Sylius\NotificationSenderPlugin\Provider\PluginsProviderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetUpdatedPluginsAction
{
    public function __construct(private PluginsProviderInterface $pluginsProvider)
    {
    }

    public function __invoke(): JsonResponse
    {
        return JsonResponse::fromJsonString(json_encode($this->pluginsProvider->provide()));
    }
}
