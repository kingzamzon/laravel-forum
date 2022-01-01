<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        App\User::truncate();
        App\Thread::truncate();
        App\Reply::truncate();

        App\User::flushEventListeners();
        App\Thread::flushEventListeners();
        App\Reply::flushEventListeners();

        factory(App\User::class, 40)->create();
        $threads = factory(App\Thread::class, 40)->create();
        $threads->each(function ($thread) {
            factory(App\Reply::class, 10)->create(['thread_id' => $thread->id]);
        });
    }
}
