<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 23.11.2018
 * Time: 22:35
 */

namespace App\Service;


final class StatusModel
{
    public const IN_WORK = 1;
    public const COMPLETED = 2;

    public const STATUS_MESSAGES = [
        self::IN_WORK => 'in_work',
        self::COMPLETED => 'completed'
    ];
}