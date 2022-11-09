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

namespace Sylius\NotificationSenderPlugin\Provider;

use Composer\Semver\Comparator;
use Sylius\NotificationSenderPlugin\Registry\PackageVersionsRegistryInterface;

final class UpdatedPluginsProvider implements PluginsProviderInterface
{
    public function __construct(
        private PackageVersionsRegistryInterface $installedPackagesRegistry,
        private PackageVersionProviderInterface $versionProvider,
    ) {
    }

    public function provide(): array
    {
        $packages = $this->installedPackagesRegistry->getPackages();

        return array_values(array_filter($packages, function (string $package): bool {
            $installedVersion = $this->installedPackagesRegistry->getVersion($package);
            $upstreamVersion = $this->versionProvider->provide($package);

            return Comparator::greaterThan($upstreamVersion, $installedVersion);
        }));
    }
}
