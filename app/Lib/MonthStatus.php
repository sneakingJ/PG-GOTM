<?php

namespace App\Lib;

/**
 *
 */
enum MonthStatus: String
{
    case READY = 'ready';
    case NOMINATION = 'nomination';
    case JURY = 'jury';
    case VOTING = 'voting';
    case PLAYING = 'playing';
    case OVER = 'over';
}
