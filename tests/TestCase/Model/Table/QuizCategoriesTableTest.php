<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\QuizCategoriesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\QuizCategoriesTable Test Case
 */
class QuizCategoriesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\QuizCategoriesTable
     */
    public $QuizCategories;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.quiz_categories'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $config = TableRegistry::exists('QuizCategories') ? [] : ['className' => 'App\Model\Table\QuizCategoriesTable'];
        $this->QuizCategories = TableRegistry::get('QuizCategories', $config);
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
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
