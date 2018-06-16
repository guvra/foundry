<?php
/**
 * Foundry Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Foundry\Tests\Builder;

use Foundry\Builder\BuilderFactory;
use Foundry\Builder\Statement\Select;
use Foundry\Tests\TestCase;

/**
 * Test the builder factory.
 */
class BuilderFactoryTest extends TestCase
{
    public function testBuilderCreation()
    {
        $builderFactory = new BuilderFactory($this->connection);
        $builderFactory->addBuilder('test', Select::class);
        $this->assertInstanceOf(Select::class, $builderFactory->create('test'));
    }

    /**
     * @expectedException \UnexpectedValueException
     */
    public function testBuilderDeletion()
    {
        $builderFactory = new BuilderFactory($this->connection);
        $this->assertInstanceOf(Select::class, $builderFactory->create('select'));
        $builderFactory->removeBuilder('select');
        $builderFactory->create('select');
    }
}
