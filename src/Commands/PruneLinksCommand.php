<?php

namespace Linkeys\UrlSigner\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Linkeys\UrlSigner\Models\Group;
use Linkeys\UrlSigner\Models\Link;

class PruneLinksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'signed-url:prune 
                            {--expired : Prune all links that have expired}
                            {--used : Prune all links that have been used}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune expired and used signed url links from the database';

    /**
     * Execute the console command.
     */

    public function handle(): int
    {
        if ($this->option('expired')) {
            $query = Link::query()
                ->where('expiry', '<', now());

            $this->info(sprintf('Pruning %s expired links', $query->count()));

            $query->delete();
        }

        if ($this->option('used')) {
            $groupQuery = Group::query()
                ->whereHas('links', function (Builder $query) {
                    $query->where('clicks', '>', 0);
                });

            $query = Link::query()
                ->where('clicks', '>', 0)
                ->orWhereHas('group', function (Builder  $query) {
                    $query->whereHas('links', function (Builder $query) {
                        $query->where('clicks', '>', 0);
                    });
                });

            $this->info(sprintf('Pruning %s used groups', $groupQuery->count()));
            $this->info(sprintf('Pruning %s used links', $query->count()));

            $groupIds = $groupQuery->pluck('id')->toArray();
            $query->delete();
            Group::whereIn('id', $groupIds)->delete();
        }

        return self::SUCCESS;
    }

}