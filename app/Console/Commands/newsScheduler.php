<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Blog;

class newsScheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'News Schedule';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = Blog::where('status','schedule')->whereDate('schedule_at','<=',now())->update(['status'=>'publish']);
        return Command::SUCCESS;
    }
}
