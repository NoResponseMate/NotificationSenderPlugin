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

namespace spec\Sylius\NotificationSenderPlugin\Registry;

use PhpSpec\ObjectBehavior;

final class InstalledPluginsRegistrySpec extends ObjectBehavior
{
    function it_returns_all_registered_packages(): void
    {
        $this->addVersion('test/package1', '1.0.0');
        $this->addVersion('package', '1.3');
        $this->addVersion('test', '0.1');

        $this->getPackages()->shouldReturn([
            'test/package1',
            'package',
            'test',
        ]);
    }

    function it_throws_exception_when_getting_non_registered_package(): void
    {
        $this
            ->shouldThrow(\LogicException::class)
            ->during('getVersion', ['n/a/package'])
        ;
    }

    function it_keeps_record_of_registered_package_versions(): void
    {
        $this->addVersion('package/1', '1.0.0');

        $this->getVersion('package/1')->shouldReturn('1.0.0');
    }
}
