<?php

namespace Tests\Unit;

use App\ImageResize;
use App\Jobs\ResizeImageJob;
use App\Service\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Ramsey\Uuid\UuidInterface;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     * @throws \Exception
     */
    public function testCreateResizeJob()
    {
        Bus::fake();

        $imageFakeFactory = UploadedFile::fake();
        $uploadedFile = $imageFakeFactory->image('fake.png');
        $imageResizeMock = \Mockery::mock('overload:' . ImageResize::class);
        $imageResizeMock->shouldReceive('save');

        $imageService = new ImageService();
        $imageService->createResizeJob($uploadedFile, 400, 400);

        Bus::assertDispatched(ResizeImageJob::class, function (ResizeImageJob $imageResize) use ($imageResizeMock) {
            $rClass = new \ReflectionClass($imageResize);
            $rProp = $rClass->getProperty('imageResize');
            $rProp->setAccessible(true);
            $object = $rProp->getValue($imageResize);
            $this->assertTrue($object->id instanceof UuidInterface);
            $this->assertTrue($object->width === 400);
            $this->assertTrue($object->height === 400);
            $this->assertTrue($object->original_path === sprintf('original/%s.png', (string)$object->id));

            return true;
        });
    }
}
