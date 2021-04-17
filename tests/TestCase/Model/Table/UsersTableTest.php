<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.users',
        'app.workers'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = TableRegistry::get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

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
     * Test searchManager method
     *
     * @return void
     */
    public function testSearchManager()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationUserRegister method
     *
     * @return void
     */
    public function testValidationUserRegister()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationUserMobile method
     *
     * @return void
     */
    public function testValidationUserMobile()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationProfile method
     *
     * @return void
     */
    public function testValidationProfile()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationContactUs method
     *
     * @return void
     */
    public function testValidationContactUs()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationChangePassword method
     *
     * @return void
     */
    public function testValidationChangePassword()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationForgotPassword method
     *
     * @return void
     */
    public function testValidationForgotPassword()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationAdminLogin method
     *
     * @return void
     */
    public function testValidationAdminLogin()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationAddEditCustomer method
     *
     * @return void
     */
    public function testValidationAddEditCustomer()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationAddEditStore method
     *
     * @return void
     */
    public function testValidationAddEditStore()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationValidateImportFile method
     *
     * @return void
     */
    public function testValidationValidateImportFile()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
