<?php
/**
 * PHP Query Builder.
 *
 * @copyright 2018 guvra
 * @license   MIT Licence
 */
namespace Tests\Builder;

use Guvra\Builder\BuilderFactory;
use Guvra\Builder\Statement\Select;
use Tests\AbstractTestCase;

/**
 * Test the builder factory.
 */
class BuilderFactoryTest extends AbstractTestCase
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
