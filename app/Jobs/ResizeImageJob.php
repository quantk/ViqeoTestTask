<?php

namespace App\Jobs;

use App\ImageResize;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Psr\Log\LoggerInterface;

class ResizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var ImageResize
     */
    private $imageResize;

    /**
     * Create a new job instance.
     *
     * @param ImageResize $imageResize
     */
    public function __construct(ImageResize $imageResize)
    {
        $this->imageResize = $imageResize;
    }

    /**
     * Execute the job.
     *
     * @param ImageManager $imageManager
     * @param LoggerInterface $logger
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(
        ImageManager $imageManager,
        LoggerInterface $logger
    )
    {
        $image = Storage::get($this->imageResize->original_path);
        $width = $this->imageResize->width;
        $height = $this->imageResize->height;
        $image = $imageManager->make($image);
        if ($width && !$height) {
            $image = $image->widen($width);
        }
        if (!$width && $height) {
            $image = $image->heighten($height);
        }
        if ($width && $height) {
            $image = $image->resize($width, $height);
        }

        $savePath = sprintf('%s/%s.%s',
            env('RESIZED_IMAGE_PATH'),
            (string)$this->imageResize->id,
            'png'
        );

        $result = Storage::put(
            $savePath,
            (string)$image->encode('png')
        );

        $image->destroy();

        if (false === $result) {
            $message = sprintf("Can't save resized image in %s", $savePath);
            $logger->critical($message);
            throw new \RuntimeException($message);
        }

        $this->imageResize->resized_path = $savePath;
        $this->imageResize->complete();
        $this->imageResize->destroy_time = Carbon::now()->addHour();
        $this->imageResize->save();
    }
}
