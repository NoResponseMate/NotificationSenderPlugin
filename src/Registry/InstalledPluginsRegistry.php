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

namespace Sylius\NotificationSenderPlugin\Registry;

final class InstalledPluginsRegistry implements PackageVersionsRegistryInterface
{
    /** @var string[]  */
    private array $packagesMap = [];

    public function getVersion(string $package): string
    {
        if (!isset($this->packagesMap[$package])) {
            throw new \LogicException(sprintf('Package "%s" is not registered.', $package));
        }

        return $this->packagesMap[$package];
    }

    public function addVersion(string $package, string $version): void
    {
        $this->packagesMap[$package] = $version;
    }
}
