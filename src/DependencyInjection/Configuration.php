<?php

declare(strict_types=1);

namespace Sylius\NotificationSenderPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('sylius_notification_sender');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
