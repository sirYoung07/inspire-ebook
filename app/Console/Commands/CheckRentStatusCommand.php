<?php

namespace App\Console\Commands;

use App\Models\Book;
use App\Models\RentedBooks;
use Illuminate\Console\Command;

class CheckRentStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:check-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check rentage status and update availability';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // to change availability for rent extension in rented_books table
        $expiredRecords = RentedBooks::
            where('end_rent_date', '<', now())
            ->get();
            
        RentedBooks::whereIn('id', $expiredRecords->pluck('id'))
                                        ->update(['is_available' => false]);  

        
       
        // to change availablity for renting book in books table
        $rentedBooks_id = RentedBooks::where('end_rent_date', '<', now())
                                    ->pluck('book_id')
                                    ->toArray();

       Book::whereIn('id', $rentedBooks_id)->update(['available' => true]);

       $this->info('Books availablility status checked and updated successfully.');

    }
}
