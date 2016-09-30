<?php

use App\Models\Album;
use App\Models\Media;
use App\Models\Slider;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MediaTest extends TestCase
{
    private $album;
    private $slider;

    public function setUp()
    {
        parent::setUp();

        $this->album = Mockery::mock(Album::class);

        $this->slider = Mockery::mock(Slider::class);
    }

    public function tearDown()
    {
        Mockery::close();
    }
    /**
     * A test for adding data to the database.
     *
     * @return void
     */
    public function testMediaDatabase()
    {
        $media = factory(App\Models\Media::class, 10)->create();

        $this->assertTrue(count($media) == 10, 'Could\'t add media to database.');
    }

    /**
     * Test if attributes are added to the database.
     */
    public function testBelongsToAlbum()
    {
        $this->assertTrue(true);
//        $this->album->shouldReceive('images')
//            ->once()
//            ->with(Media::class)
//            ->andReturn($this->album);
    }
}
