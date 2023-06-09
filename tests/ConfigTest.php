<?php

namespace Tests\Exan\Config;

use Exan\Config\Config;
use Exan\Config\Exceptions\InvalidConfigException;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @dataProvider configProvider
     */
    public function testItRetrievesConfigurations(string $key, mixed $expected)
    {
        $config = new Config(__DIR__ . '/config');

        $this->assertEquals(
            $expected,
            $config->get($key),
        );
    }

    public static function configProvider(): array
    {
        return [
            'Simple' => [
                'key' => 'test-config.simple',
                'expected' => '::simple::',
            ],
            'Two levels deep' => [
                'key' => 'test-config.two-levels.deep',
                'expected' => '::two-levels-deep::',
            ],
            'Int' => [
                'key' => 'test-config.int',
                'expected' => 1,
            ],
            'File only' => [
                'key' => 'test-config',
                'expected' => [
                    'simple' => '::simple::',
                    'two-levels' => [
                        'deep' => '::two-levels-deep::',
                    ],
                    'int' => 1
                ],
            ]
        ];
    }

    public function testItThrowsAnErrorIfAConfigFileWasInvalid()
    {
        $config = new Config(__DIR__ . '/config');

        $this->expectException(InvalidConfigException::class);

        $config->get('invalid-config.key');
    }

    /**
     * @dataProvider returnsDefaultProvider
     */
    public function testItReturnsDefault(string $key)
    {
        $config = new Config(__DIR__ . '/config');

        $this->assertEquals(
            '::default::',
            $config->get($key, '::default::'),
        );
    }

    public static function returnsDefaultProvider(): array
    {
        return [
            'Empty key' => [
                'key' => '',
            ],
            'Non-existant file' => [
                'key' => 'doesnt-exist',
            ],
            'Key not found' => [
                'key' => 'test-config.doesnt-exist',
            ],
        ];
    }
}
