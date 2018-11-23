<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 23.11.2018
 * Time: 16:23
 */

namespace App\Service;


use App\ImageResize;
use App\Jobs\ResizeImageJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ImageService
{

    /**
     * @throws \Exception
     */
    public function createResizeJob(UploadedFile $uploadedFile, ?int $width, ?int $height): UuidInterface
    {
        $jobId = Uuid::uuid4();
        $path = Storage::putFileAs(
            env('ORIGINAL_IMAGE_PATH'),
            $uploadedFile,
            sprintf('%s.%s', (string)$jobId, $uploadedFile->getClientOriginalExtension())
        );
        $imageResize = ImageResize::create([
            'id' => $jobId,
            'width' => $width,
            'height' => $height,
            'original_path' => $path,
            'status' => StatusModel::IN_WORK
        ]);
        ResizeImageJob::dispatch($imageResize);

        return $jobId;
    }
}