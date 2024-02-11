<?php

namespace App\Console\Commands;

use App\Models\Destination;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class DestinationsAddSlug extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slug-dest';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add slugs to destinations';

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
     */
    public function handle()
    {
        $bar = $this->output->createProgressBar(Destination::count());

        Destination::chunk(50, function ($destinations) use ($bar) {
            foreach ($destinations as $destination) {
                $destination->slug = Str::slug($destination->nom, '-');
                $destination->save();
            }

            $bar->advance();
        });

        $bar->finish();

        return 0;
    }
}
