<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    private $testAttributes = [
        'firstname' => 'Martin',
        'lastname' => 'Kok',
        'username' => 'skytz',
        'email' => 'info@skytz.nl',
    ];

    /**
     * Test if adding users to database works.
     *
     * @return void
     */
    public function testDatabase()
    {
        $users = factory(App\Models\User::class, 10)->make();

        $this->assertTrue(count($users) == 10);
    }

    /**
     * Test if attributes are added to the database.
     */
    public function testAttributes()
    {
        $user = factory(App\Models\User::class)->create($this->testAttributes);

        $this->seeInDatabase('users', $this->testAttributes);
    }

    /**
     * Test if getName method returns the concatenated name.
     */
    public function testGetName()
    {
        $user = factory(App\Models\User::class)->create($this->testAttributes);

        $this->assertEquals($user->getName(), "Martin Kok");
    }
}
