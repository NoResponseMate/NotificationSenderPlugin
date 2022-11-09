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
use Packagist\Api\Client;
use Packagist\Api\Result\Package;
use Packagist\Api\Result\Package\Version;

final class PackageVersionProvider implements PackageVersionProviderInterface
{
    public function __construct(private Client $client)
    {
    }

    public function provide(string $package): string
    {
        try {
            $packages = $this->client->getComposerReleases($package);

            if (empty($packages)) {
                return '';
            }

            $composerPackage = $packages[0];
        } catch (\RuntimeException) {
            return '';
        }

        return $this->getLatest($composerPackage);
    }

    private function getLatest(Package $package): string
    {
        $versions = array_map(static fn (Version $version) => $version->getVersion(), $package->getVersions());
        if ([] === $versions) {
            return '';
        }

        $latestVersion = array_reduce(
            $versions,
            static function ($carry, $version): string {
                if (null === $carry) {
                    return $version;
                }

                return Comparator::greaterThan($version, $carry) ? $version : $carry;
            },
        );

        return preg_replace('/^v/', '', $latestVersion) ?? '';
    }
}
