<?php

namespace App\Console\Commands;

use App\ImageResize;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Psr\Log\LoggerInterface;

class ImageGarbageCollectorCommand extends Command
{
    const CHUNK_SIZE = 100;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'viqeotest:image:garbage-collector';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Create a new command instance.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ImageResize::query()->where('destroy_time', '<', Carbon::now())->chunk(self::CHUNK_SIZE, function ($imageResizes) {
            foreach ($imageResizes as $imageResize) {
                /** @var $imageResize ImageResize */
                $imagePaths = [$imageResize->original_path, $imageResize->resized_path];
                $result = Storage::delete($imagePaths);
                if (false === $result) {
                    $this->logger->critical(sprintf("Can't delete files: [%s]", implode(',', $imagePaths)));
                    continue;
                }

                $imageResize->delete();
            }
        });
    }
}
