<?php

namespace App\Http\Controllers;

use App\Http\Requests\UploadImageRequest;
use App\ImageResize;
use App\Service\ImageService;
use App\Service\StatusModel;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageController extends Controller
{
    /**
     * @param UploadImageRequest $request
     * @param ImageService $service
     * @return JsonResponse
     * @throws \Exception
     */
    public function upload(UploadImageRequest $request, ImageService $service)
    {
        $jobId = $service->createResizeJob($request->image, $request->width, $request->height);

        return $this->json([
            'id' => (string)$jobId
        ]);
    }

    /**
     * @param string $id
     * @return JsonResponse
     */
    public function getImage(string $id)
    {
        $id = Uuid::fromString($id);
        $imageResize = ImageResize::find($id);
        if (null === $imageResize) {
            throw new NotFoundHttpException('Image not found');
        }

        if (false === $imageResize->isCompleted()) {
            return $this->json([
                'status_code' => $imageResize->status,
                'status' => StatusModel::STATUS_MESSAGES[$imageResize->status]
            ]);
        }

        try {
            $resizedImagePath = Storage::url($imageResize->resized_path);
            return $this->json([
                'status_code' => $imageResize->status,
                'status' => StatusModel::STATUS_MESSAGES[$imageResize->status],
                'url' => $resizedImagePath
            ]);
        } catch (FileNotFoundException $e) {
            throw new NotFoundHttpException('Image not found');
        }
    }
}
