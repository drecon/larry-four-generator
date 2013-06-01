<?php

use \LarryFour\Parser;
use \LarryFour\Parser\FieldParser;
use \LarryFour\Parser\ModelDefinitionParser;
use \LarryFour\ModelList;
use \LarryFour\MigrationList;
use \LarryFour\Generator\MigrationGenerator;

class MigrationGeneratorTest extends PHPUnit_Framework_TestCase
{
    private $parsed = null;

    public function testGeneratedMigrationContentsForUserTable()
    {
        $expected = file_get_contents(__DIR__ . '/data/migration_user');
        $parsed = $this->getSampleParsedObject();
        $migrations = $parsed['migrationList']->all();
        $user = $migrations['User'];

        $generator = new MigrationGenerator();

        $this->assertEquals($expected, $generator->generate($user));
    }

    private function getSampleParsedObject()
    {
        if (is_null($this->parsed))
        {
            $this->parsed = $this->getParsedOutput($this->getSampleInput());
        }

        return $this->parsed;
    }

    private function getParsedOutput($input)
    {
        $p = new Parser(
            new FieldParser(),
            new ModelDefinitionParser(),
            new ModelList(),
            new MigrationList());
        return $p->parse($input);
    }

    private function getSampleInput()
    {
        return <<<EOF
User users; hm Post; btm Role; mm Image imageable; hm Stuff stuffer_id; btm Thumb t_u u_id t_id;
    id increments
    username string 50; default "hello world"; nullable;
    password string 64
    email string 250
    type enum admin, moderator, user

Post; bt User; mm Image imageable;
    timestamps
    title string 250
    content text
    rating decimal 5 2

Image
    timestamps

Role
    timestamps

Stuff; bt User;
    timestamps

Thumb
    timestamps
EOF;
    }
}