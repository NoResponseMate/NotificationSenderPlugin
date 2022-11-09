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

namespace spec\Sylius\NotificationSenderPlugin\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\NotificationSenderPlugin\Provider\PackageVersionProviderInterface;
use Sylius\NotificationSenderPlugin\Provider\PluginsProviderInterface;
use Sylius\NotificationSenderPlugin\Provider\UpdatedPluginsProvider;
use Sylius\NotificationSenderPlugin\Registry\PackageVersionsRegistryInterface;

final class UpdatedPluginsProviderSpec extends ObjectBehavior
{
    function let(
        PackageVersionsRegistryInterface $installedPackagesRegistry,
        PackageVersionProviderInterface $versionProvider,
    ): void {
        $this->beConstructedWith($installedPackagesRegistry, $versionProvider);
    }

    function it_implements_plugins_provider_interface(): void
    {
        $this->shouldImplement(PluginsProviderInterface::class);
    }

    function it_returns_an_empty_array_when_there_are_no_plugins_registered(
        PackageVersionsRegistryInterface $installedPackagesRegistry,
        PackageVersionProviderInterface $versionProvider,
    ): void {
        $installedPackagesRegistry->getPackages()->willReturn([]);

        $installedPackagesRegistry->getVersion(Argument::any())->shouldNotBeCalled();
        $versionProvider->provide(Argument::any())->shouldNotBeCalled();

        $this->provide()->shouldReturn([]);
    }

    function it_returns_an_empty_array_when_no_plugin_can_be_updated(
        PackageVersionsRegistryInterface $installedPackagesRegistry,
        PackageVersionProviderInterface $versionProvider,
    ): void {
        $installedPackagesRegistry->getPackages()->willReturn([
            'package',
            'package/test',
        ]);

        $installedPackagesRegistry->getVersion('package')->willReturn('1.0.0');
        $versionProvider->provide('package')->willReturn('1.0.0');

        $installedPackagesRegistry->getVersion('package/test')->willReturn('0.2');
        $versionProvider->provide('package/test')->willReturn('0.2');

        $this->provide()->shouldReturn([]);
    }

    function it_returns_plugins_that_can_be_updated(
        PackageVersionsRegistryInterface $installedPackagesRegistry,
        PackageVersionProviderInterface $versionProvider,
    ): void {
        $installedPackagesRegistry->getPackages()->willReturn([
            'package/updated',
            'package/old',
            'package/updated2',
        ]);

        $installedPackagesRegistry->getVersion('package/updated')->willReturn('1.0.0');
        $versionProvider->provide('package/updated')->willReturn('1.0.1');

        $installedPackagesRegistry->getVersion('package/old')->willReturn('1.2');
        $versionProvider->provide('package/old')->willReturn('1.2');

        $installedPackagesRegistry->getVersion('package/updated2')->willReturn('1.2');
        $versionProvider->provide('package/updated2')->willReturn('1.3');

        $this->provide()->shouldIterateAs([
            'package/updated',
            'package/updated2',
        ]);
    }
}
