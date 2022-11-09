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

namespace Tests\Sylius\NotificationSenderPlugin\Unit\DependencyInjection;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sylius\NotificationSenderPlugin\DependencyInjection\Configuration;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /** @test */
    public function empty_configuration_triggers_no_errors(): void
    {
        $this->assertConfigurationIsValid([[]], 'auth');
        $this->assertConfigurationIsValid([[]], 'plugins');
    }

    /** @test */
    public function empty_plugins_configuration_triggers_no_errors(): void
    {
        $this->assertConfigurationIsValid([['plugins' => []]], 'plugins');
    }

    /** @test */
    public function auth_token_and_packagist_url_must_be_specified(): void
    {
        $this->assertConfigurationIsInvalid(
            [['auth' => []]],
            'The child config "packagist_url" under "sylius_notification.auth" must be configured.'
        );
        $this->assertConfigurationIsInvalid(
            [['auth' => ['packagist_url' => 'url']]],
            'The child config "token" under "sylius_notification.auth" must be configured.'
        );
    }

    /** @test */
    public function auth_token_and_packagist_url_cannot_be_empty(): void
    {
        $this->assertConfigurationIsInvalid(
            [['auth' => ['packagist_url' => '', 'token' => 'token']]],
            'The path "sylius_notification.auth.packagist_url" cannot contain an empty value'
        );
        $this->assertConfigurationIsInvalid(
            [['auth' => ['packagist_url' => 'url', 'token' => '']]],
            'The path "sylius_notification.auth.token" cannot contain an empty value'
        );
    }

    /** @test */
    public function it_configures_empty_plugins(): void
    {
        $this->assertProcessedConfigurationEquals(
            [['plugins' => []]],
            ['plugins' => []],
            'plugins',
        );
    }

    /** @test */
    public function multiple_plugins_can_be_configured(): void
    {
        $this->assertProcessedConfigurationEquals(
            [['plugins' => ['package/1', 'package/2']]],
            ['plugins' => ['package/1', 'package/2']],
            'plugins',
        );
    }

    /** @test */
    public function plugins_configurations_get_merged(): void
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['plugins' => ['package/1', 'package/2']],
                ['plugins' => ['package/3', 'package/4']],
            ],
            ['plugins' => ['package/1', 'package/2', 'package/3', 'package/4']],
            'plugins',
        );
    }

    protected function getConfiguration(): ConfigurationInterface
    {
        return new Configuration();
    }
}
