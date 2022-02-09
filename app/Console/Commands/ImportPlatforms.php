<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use MarcReichel\IGDBLaravel\Models\Platform;
use App\Models\IgdbPlatform;

class ImportPlatforms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gotm:importplatforms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import all IGDB platforms via API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \MarcReichel\IGDBLaravel\Exceptions\MissingEndpointException
     */
    public function handle(): int
    {
        $platforms = Platform::with(['platform_logo'])->all();

        IgdbPlatform::truncate();
        foreach ($platforms as $platform) {
            $igdbPlatform = new IgdbPlatform();
            $igdbPlatform->igdb_id = $platform->id;
            $igdbPlatform->name = $platform->name;
            $logo = empty($platform->platform_logo) ? '' : $platform->platform_logo['url'];
            $igdbPlatform->logo = str_replace(['t_thumb', 'jpg'], ['t_cover_small', 'png'], $logo);
            $igdbPlatform->save();
        }

        $this->info(count($platforms) . ' platforms imported!');

        return 0;
    }
}
