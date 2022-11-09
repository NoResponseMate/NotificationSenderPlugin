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

use Composer\Semver\Comparator;
use Sylius\NotificationSenderPlugin\Provider\PackageVersionProviderInterface;
use Sylius\NotificationSenderPlugin\Registry\PackageVersionsRegistryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class GetUpdatedPluginsAction
{
    public function __construct(
        private PackageVersionsRegistryInterface $installedPackagesRegistry,
        private PackageVersionProviderInterface $versionProvider,
    ) {
    }

    public function __invoke(): JsonResponse
    {
        $updatedPlugins = [];
        $packages = $this->installedPackagesRegistry->getPackages();

        foreach ($packages as $package) {
            $installedVersion = $this->installedPackagesRegistry->getVersion($package);
            $upstreamVersion = $this->versionProvider->provide($package);

            if (Comparator::greaterThan($upstreamVersion, $installedVersion)) {
                $updatedPlugins[] = $package;
            }
        }

        return JsonResponse::fromJsonString(json_encode($updatedPlugins));
    }
}
