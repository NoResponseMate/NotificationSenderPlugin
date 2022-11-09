<?php

declare(strict_types=1);

namespace Sylius\NotificationSenderPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Sylius\NotificationSenderPlugin\DependencyInjection\Compiler\PackageRegistryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class SyliusNotificationSenderPlugin extends Bundle
{
    use SyliusPluginTrait;

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new PackageRegistryPass());
    }

    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
