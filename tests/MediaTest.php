<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MediaTest extends TestCase
{
    private $testAttributes = [
        'name' => 'filename.jpg',
        'description' => 'An example file.',
        'path' => 'images/filename.jpg',
        'mime' => 'image/jpeg',
        'extension' => 'jpg',
    ];

    /**
     * A test for adding data to the database.
     *
     * @return void
     */
    public function testDatabase()
    {
        $media = factory(App\Models\Media::class, 10)->make();

        $this->assertTrue(count($media) == 10);
    }

    /**
     * Test if attributes are added to the database.
     */
    public function testAttributes()
    {
        $media = factory(App\Models\Media::class)->create($this->testAttributes);

        $this->seeInDatabase('media', $this->testAttributes);
    }
}
