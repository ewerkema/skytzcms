<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    /**
     * Test if adding users to database works.
     *
     * @return void
     */
    public function testUserDatabase()
    {
        $users = factory(App\Models\User::class, 10)->create();

        $this->assertTrue(count($users) == 10, 'Couldn\'t add users to database');
    }

    /**
     * Test if getName method returns the concatenated name.
     */
    public function testGetName()
    {
        $user = factory(App\Models\User::class)->make(['firstname' => 'Martin', 'lastname' => 'Kok']);

        $this->assertEquals($user->getName(), "Martin Kok");
    }
}
