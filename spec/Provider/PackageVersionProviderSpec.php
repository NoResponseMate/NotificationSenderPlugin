<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\NotificationSenderPlugin\Provider;

use Packagist\Api\Client;
use Packagist\Api\Result\Package;
use Packagist\Api\Result\Package\Version;
use PhpSpec\ObjectBehavior;
use Sylius\NotificationSenderPlugin\Provider\PackageVersionProviderInterface;

final class PackageVersionProviderSpec extends ObjectBehavior
{
    function let(Client $client): void
    {
        $this->beConstructedWith($client);
    }

    function it_implements_packages_versions_provider_interface(): void
    {
        $this->shouldImplement(PackageVersionProviderInterface::class);
    }

    function it_returns_newest_released_version_of_packages(
        Client $client,
        Package $package,
        Version $firstVersion,
        Version $secondVersion,
        Version $thirdVersion,
    ): void {
        $client->getComposerReleases('package')->willReturn([$package]);

        $firstVersion->getVersion()->willReturn('1.0.0');
        $secondVersion->getVersion()->willReturn('2.0.0');
        $thirdVersion->getVersion()->willReturn('1.0.0@alpha');

        $package->getVersions()->willReturn([
            $firstVersion,
            $secondVersion,
            $thirdVersion,
        ]);

        $this->provide('package')->shouldReturn('2.0.0');
    }

    function it_returns_an_empty_string_when_client_threw_an_exception(Client $client): void
    {
        $client->getComposerReleases('package')->willThrow(\RuntimeException::class);

        $this->provide('package')->shouldReturn('');
    }

    function it_return_an_empty_string_when_no_package_is_found(Client $client): void
    {
        $client->getComposerReleases('package')->willReturn([]);

        $this->provide('package')->shouldReturn('');
    }

    function it_return_an_empty_string_when_package_has_no_versions_released(Client $client, Package $package): void
    {
        $client->getComposerReleases('package')->willReturn([$package]);
        $package->getVersions()->willReturn([]);

        $this->provide('package')->shouldReturn('');
    }
}
