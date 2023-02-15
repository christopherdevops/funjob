<?php
namespace App\Test\TestCase\Shell;

use App\Shell\QuizCategoriesShell;
use Cake\TestSuite\TestCase;

/**
 * App\Shell\QuizCategoriesShell Test Case
 */
class QuizCategoriesShellTest extends TestCase
{

    /**
     * ConsoleIo mock
     *
     * @var \Cake\Console\ConsoleIo|\PHPUnit\Framework\MockObject\MockObject
     */
    public $io;

    /**
     * Test subject
     *
     * @var \App\Shell\QuizCategoriesShell
     */
    public $QuizCategories;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->io = $this->getMockBuilder('Cake\Console\ConsoleIo')->getMock();
        $this->QuizCategories = new QuizCategoriesShell($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->QuizCategories);

        parent::tearDown();
    }

    /**
     * Test getOptionParser method
     *
     * @return void
     */
    public function testGetOptionParser()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test main method
     *
     * @return void
     */
    public function testMain()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
