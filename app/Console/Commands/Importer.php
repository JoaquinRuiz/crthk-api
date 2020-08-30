<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libraries\JsonAPILibrary;

class Importer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:jsonapi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from json api';
    /**
     * @var JsonAPILibrary
     */
    private $apiClient;

    public function __construct(JsonAPILibrary $apiClient)
    {
        $this->apiClient = $apiClient;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->apiClient->importUsers();
        $this->apiClient->importPosts();
        $this->apiClient->importComments();

        return 0;
    }
}
