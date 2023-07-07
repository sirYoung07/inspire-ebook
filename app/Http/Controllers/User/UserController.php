<?php

namespace App\Http\Controllers\User;
use Carbon\Carbon;
use App\Models\User;
use App\Traits\userTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\RentedBooks;
use Illuminate\Support\Facades\Auth;
use Psy\CodeCleaner\ReturnTypePass;
use Symfony\Component\Translation\Dumper\DumperInterface;

class UserController extends Controller
{
    use userTrait;

    public function manage_profile(Request $request){
        
        $user = User::find($this->getauth()->id);

        if(!$user){
            return $this->failure();
        }

        $request->validate([
            'first_name' => ['required', 'string','min:3','max:64'],
            'last_name' => ['required', 'string', 'min:3', 'max:64'],
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name =  $request->input('last_name');

        $user->save();

        return $this->success([], 'Profile Updated Successfully');

    }



    public function available_books()
    {
        return Book::where('available', true)->get();
    }
    



    public function rent_book(Request $request, $id)
    {
        $inputs = $request->validate([ 'duration' => 'required|integer ']);

        $check_rent_payment =  $this->make_rent_payment($inputs['duration'], $id);

        if($check_rent_payment === 1){
            return $this->success([], 
                 'Book rented successfully, your rentage expires in'. ' ' . $inputs['duration'] . ' ' .  'day(s)');

        }

        return $check_rent_payment;

        
    }


    


    public function extend_rent_duration(Request $request, $id){
        $inputs = $request->validate([ 'duration' => 'required|integer']);
        $book = Book::find($id);

        if(!$book){
           
            return $this->failure(['message' => 'record not found']);
        }

        $user = $this->getauth();
        $rented_book = RentedBooks::where('rentable_id', $user->id)->where('book_id', $id)->first();
        if(!$rented_book){
            return $this->failure([], 'Unauhtorized', self::UNAUTHORIZED);
        }
        
        if($rented_book->is_available == false){
            return $this->failure(['info' => 'you need to rent this book again'
        ], 'Your rentage is expired already', self::ACCESS_DENIED);

        }

        
        $wallet_balance = $user->wallet_balance;

        $book_price = $book->price;
        $total_cost = $book_price * $inputs['duration'];
        
       
        if($wallet_balance < $total_cost){

            return $this->failure([
                'wallet_balance' => $wallet_balance,
                'rentage_cost' => $total_cost,
                'amount needed more for rentage extension' => $total_cost - $wallet_balance
                ], 

                'Insufficient balance');
        }

        $new_user = User::where('id', $user->id)->first();
        $new_wallet_balance = $wallet_balance - $total_cost;
        $new_user->wallet_balance = $new_wallet_balance;
        $new_user->save();

        $rented_book->end_rent_date = now()->addDays($inputs['duration']);
        $rented_book->save();

        return $this->success(['information' => 'you have additional' . ' '. $inputs['duration'] . ' day(s) to read this book '
    ], 'Boonk rents dated extended successfully');


        
        
    }
    


    public function get_rented_books(User $user, Book $book){
        $all_books = $this->getauth()->rentedBooks;
        
        if(!$all_books){
            return $this->success(['you do not have any rentage record']);
        }
    
        return $all_books;
    }
    



    public function get_rented_book_detail($id) {

        $book = Book::find($id);

        if(!$book){
            return $this->failure(['message' => 'record not found']);
        }
        
        $single_book = $this->getauth()->rentedBooks->find($id);
        
        if(!$single_book){
            return $this->failure(['Message' => 'You do not have access'], 'Book not rented by you', 401);
        }

        return $single_book;

        
    }



   



    protected function make_rent_payment(int $num_of_days, $id) : int|object{
        $book = Book::find($id);

        if(!$book){
           
            return $this->failure(['message' => 'record not found']);
        }

        if($book->available == false){
            return $this->failure([], 'this book is not available for rentage');
        }

        
        $user = $this->getauth();
        $wallet_balance = $user->wallet_balance;

        $book_price = $book->price;
        $total_cost = $book_price * $num_of_days;
        
       
        if($wallet_balance < $total_cost){

            return $this->failure([
                'wallet_balance' => $wallet_balance,
                'rentage_cost' => $total_cost,
                'amount needed more for rentage' => $total_cost - $wallet_balance
                ], 

                'Insufficient balance');
        }

        $new_user = User::where('id', $user->id)->first();
        $new_wallet_balance = $wallet_balance - $total_cost;
        $new_user->wallet_balance = $new_wallet_balance;
        $new_user->save();

        $rentedbook = new RentedBooks();
        $rentedbook->books()->associate($book);
        $rentedbook->rentable()->associate($user);
        $rentedbook->start_rent_date = now();
        $rentedbook->end_rent_date = now()->addDays($num_of_days);
        $rentedbook->total_cost = $total_cost;
        $rentedbook->save();

        $book->available = false;
        $book->save();
        

       return 1;
       

    }

}