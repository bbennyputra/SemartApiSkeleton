<?php

declare(strict_types=1);

namespace Alpabit\ApiSkeleton\Generator;

use Alpabit\ApiSkeleton\Generator\Model\GeneratorInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Muhamad Surya Iksanudin<surya.iksanudin@alpabit.com>
 */
final class GeneratorFactory
{
    /**
     * @var GeneratorInterface[]
     */
    private $generators = [];

    public function __construct(iterable $generators)
    {
        $this->generators = $generators;
    }

    public function generate(\ReflectionClass $class, OutputInterface $output): void
    {
        foreach ($this->generators as $generator) {
            $generator->generate($class, $output);
        }
    }
}
