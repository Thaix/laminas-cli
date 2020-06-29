<?php

/**
 * @see       https://github.com/laminas/laminas-cli for the canonical source repository
 * @copyright https://github.com/laminas/laminas-cli/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-cli/blob/master/LICENSE.md New BSD License
 */

declare(strict_types=1);

namespace Laminas\Cli;

use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

use function array_keys;

/**
 * @internal
 */
final class ContainerCommandLoaderNoTypeHint implements CommandLoaderInterface
{
    /** @var ContainerInterface */
    private $container;

    /** @var string[] */
    private $commandMap;

    public function __construct(ContainerInterface $container, array $commandMap)
    {
        $this->container  = $container;
        $this->commandMap = $commandMap;
    }

    /**
     * @param string $name
     */
    public function get($name): Command
    {
        $command = $this->container->has($this->commandMap[$name])
            ? $this->container->get($this->commandMap[$name])
            : new $this->commandMap[$name]();
        $command->setName($name);
        return $command;
    }

    /**
     * @param string $name
     */
    public function has($name): bool
    {
        if ($this->container->has($this->commandMap[$name])) {
            return true;
        }

        return isset($this->commandMap[$name]);
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return array_keys($this->commandMap);
    }
}