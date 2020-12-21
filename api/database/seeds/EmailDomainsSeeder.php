<?php

use App\Models\EmailDomain;
use Illuminate\Database\Seeder;

class EmailDomainsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Add tcm domain for first client
        factory(EmailDomain::class)->create([
            'domain'     => 'tcm.com',
            'client_id'  => '1',
        ]);

        // Random email domains for random users
        factory(EmailDomain::class, 30)->create();
    }
}
