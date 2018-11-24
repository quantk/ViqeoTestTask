<?php

namespace Tests\Unit;

use App\ImageResize;
use App\Jobs\ResizeImageJob;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Psr\Log\NullLogger;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class ResizeImageJobTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function testHandleWiden()
    {
        $mock = new ImageResize();
        $mock->width = 400;
        $mock->height = null;
        $id = Uuid::uuid4();
        $mock->id = $id;
        $mock->original_path = 'images';
        $job = new ResizeImageJob($mock);

        $imageManager = $this->createMock(ImageManager::class);
        $image = $this->createMock(Image::class);
        $image->method('__call')->withConsecutive(['widen', [400]], ['destroy'])->willReturn($image);
        $imageManager->method('make')->willReturn($image);
        $logger = new NullLogger();
        $job->handle($imageManager, $logger);

        static::assertTrue($mock->resized_path === sprintf('%s/%s.%s', env('RESIZED_IMAGE_PATH'), (string)$id, 'png'));
    }

    public function testHandleHeighten()
    {
        $mock = new ImageResize();
        $mock->width = null;
        $mock->height = 400;
        $id = Uuid::uuid4();
        $mock->id = $id;
        $mock->original_path = 'images';
        $job = new ResizeImageJob($mock);

        $imageManager = $this->createMock(ImageManager::class);
        $image = $this->createMock(Image::class);
        $image->method('__call')->withConsecutive(['heighten', [400]], ['destroy'])->willReturn($image);
        $imageManager->method('make')->willReturn($image);
        $logger = new NullLogger();
        $job->handle($imageManager, $logger);

        static::assertTrue($mock->resized_path === sprintf('%s/%s.%s', env('RESIZED_IMAGE_PATH'), (string)$id, 'png'));
    }

    public function testHandleResize()
    {
        $mock = new ImageResize();
        $mock->width = 400;
        $mock->height = 400;
        $id = Uuid::uuid4();
        $mock->id = $id;
        $mock->original_path = 'images';
        $job = new ResizeImageJob($mock);

        $imageManager = $this->createMock(ImageManager::class);
        $image = $this->createMock(Image::class);
        $image->method('__call')->withConsecutive(['resize', [400, 400]], ['destroy'])->willReturn($image);
        $imageManager->method('make')->willReturn($image);
        $logger = new NullLogger();
        $job->handle($imageManager, $logger);

        static::assertTrue($mock->resized_path === sprintf('%s/%s.%s', env('RESIZED_IMAGE_PATH'), (string)$id, 'png'));
    }

    protected function setUp()
    {
        parent::setUp();
        Storage::shouldReceive('get')->once()->andReturn('');
        Storage::shouldReceive('put')->once()->andReturn('true');
        $imageResizeMock = \Mockery::mock('overload:' . ImageResize::class);
        $imageResizeMock->shouldReceive('save');
        $imageResizeMock->shouldReceive('complete');
    }
}
