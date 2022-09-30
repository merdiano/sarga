<?php

namespace Sarga\Admin\Console\Commands;

use Illuminate\Console\Command;
use MeiliSearch\Client;
use function PHPUnit\Framework\throwException;

class UpdateMeilisearchIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Meilisearch\'s index and filterable attributes';

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
        $client = new Meilisearch\Client(env('MEILISEARCH_HOST','http://127.0.0.1:7700'),env('MEILISEARCH_KEY','Y0urVery-S3cureAp1K3y'));

        $this->updateSortableAttributes($client);

        $this->updateFilterableAttributes($client);

        return Command::SUCCESS;
    }

    protected function updateSortableAttributes(Client $client):void
    {
        $client->index('products_index')->updateSortableAttributes([
            'name',
            'product_id',
        ]);

        $this->info('Updated sortable attributes...');
    }

    protected function updateFilterableAttributes(Client $client): void
    {
        $client->index('products_index')->updateFilterableAttributes([
            'status',
            'visible_individually',

        ]);

        $this->info('Updated filterable attributes...');
    }
}