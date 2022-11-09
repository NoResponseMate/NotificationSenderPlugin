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

namespace Sylius\NotificationSenderPlugin\DependencyInjection\Compiler;

use Composer\InstalledVersions;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class PackageRegistryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $registryDefinition = $container->findDefinition('sylius_notification.packages_registry');

        $notificationConfig = $container->getExtensionConfig('sylius_notification');

        foreach ($notificationConfig['plugins'] as ['package' => $package]) {
            if (false === InstalledVersions::isInstalled($package, false)) {
                continue;
            }

            $version = InstalledVersions::getPrettyVersion($package);

            $registryDefinition->addMethodCall('addVersion', [$package, $version]);
        }
    }
}
