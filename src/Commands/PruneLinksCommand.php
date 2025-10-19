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
                            {--used : Prune all links that have been used at all}
                            {--used-fully : Prune all links that have been used in full (i.e. met the click limits)}';

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

        if ($this->option('used') || $this->option('used-fully')) {
            if($this->option('used')) { // Delete all links that have been used at all.
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

            } elseif($this->option('used-fully')) {
                $groupQuery = Group::query()
                    ->whereHas('links', function (Builder $query) {
                        // Either match rows where the link clicks are >= the limit
                        $query->whereColumn('clicks', '>=', 'click_limit')
                            // Or where there's at least one click and no set limit
                            ->orWhere(function (Builder $query) {
                                $query->where(function (Builder $query) {
                                    $query->whereNull('click_limit')
                                        ->orWhere('click_limit', 0);
                                })
                                    ->where('clicks', '>', 0);
                            });
                    });

                $query = Link::query()
                    // Either match rows where the link clicks are >= the limit
                    ->whereColumn('clicks', '>=', 'click_limit')
                    // Or where there's at least one click and no set limit
                    ->orWhere(function (Builder $query) {
                        $query->where(function (Builder $query) {
                            $query->whereNull('click_limit')
                                ->orWhere('click_limit', 0);
                        })
                            ->where('clicks', '>', 0);
                    })
                    // Or where the group has a used click
                    ->where('clicks', '>', 0)
                    ->orWhereHas('group', function (Builder $query) {
                        $query->whereHas('links', function (Builder $query) {
                            // Either match rows where the link clicks are >= the limit
                            $query->whereColumn('clicks', '>=', 'click_limit')
                                // Or where there's at least one click and no set limit
                                ->orWhere(function (Builder $query) {
                                    $query->where(function (Builder $query) {
                                        $query->whereNull('click_limit')
                                            ->orWhere('click_limit', 0);
                                    })
                                        ->where('clicks', '>', 0);
                                });
                        });
                    });
            }

            $this->info(sprintf('Pruning %s used groups', $groupQuery->count()));
            $this->info(sprintf('Pruning %s used links', $query->count()));

            $groupIds = $groupQuery->pluck('id')->toArray();
            $linkIds = $query->pluck('id')->toArray();

            Link::whereIn('id', $linkIds)->delete();
            Group::whereIn('id', $groupIds)->delete();
        }

        return self::SUCCESS;
    }

}