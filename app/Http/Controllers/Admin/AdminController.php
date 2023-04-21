<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\UserController;
use App\Models\Book;
use App\Traits\userTrait;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\returnSelf;

class AdminController extends Controller
{
    use userTrait;

    public function createbook(Request $request, Book $book){
        $request->validate([
            'author_name' => ['required', 'string', 'min:5'],
            'description' => ['required', 'string', 'min:10'],
            'price' => ['required', ],
            'file' => ['required', 'mimes:pdf,docx', 'max:5120']
        ]);

        
        $book->author_name = $request->input('author_name');
        
        $book->description = $request->input('description');
        $book->price = $request->input('price');
        

        if($request->hasFile('file')){

            $file = $request->file('file');
            $file_name = $book->id . '_'.  time() . $file->getClientOriginalExtension();
            $file_path = $file->storeAs('books', $file_name, 'public');
            $book->book_path = $file_path;
        }

        $this->getauth()->books()->save($book);
        return $this->success(['message' => 'You have successfully upload your book']);

    }

    public function viewbook(){
        $all_books = $this->getauth()->books()->get();
        if(!$all_books){
            return $this->success(['you have not upload any books']);
        }
        return $all_books;
    }

    public function single_book($id) {
        $book = Book::find($id);

        if(!$book){
            return $this->failure(['message' => 'record not found']);
        }

        $single_book = $this->getauth()->books()->find($id);
        
        if(!$single_book){
            return $this->failure(['message' => 'this particular book is not created by you'], 'You do not have access to this book', 401);
        }

        return $single_book;
    }

    public function update($id, Request $request, Book $book){
        

        $credentials = $request->validate([
            'author_name' => ['required', 'string', 'min:5'],
            'description' => ['required', 'string', 'min:10'],
            'price' => ['required'],
            'file' => ['required', 'mimes:pdf,docx', 'max:5120']
        ]);


        
        if($request->hasFile('file')){

            $file = $request->file('file');
            $file_name = $book->id . '_'.  time() . '_' . $file->getClientOriginalExtension();
            $file_path = $file->storeAs('books', $file_name, 'public');
            $book->book_path = $file_path;

        }

        $book = BooK::find($id);
        if(!$book){
            return $this->failure(['message' => 'record not found']);
        }

        $valid_book = $this->getauth()->books()->find($id);
        
        if(!$valid_book){
            return $this->failure(['message' => 'this particular book is not created by you'], 'You do not have access to this book', 401);
        }
        $update = $valid_book->update($credentials);
        if (!$update){
            return 'error';
        }

        
        return $this->success([], 'book has been updated');
    }

    public function ban($id){

       $book = Book::find($id);

        if(!$book){
            return $this->failure(['message' => 'record not found']);
            
        }

        $single_book = $this->getauth()->books()->find($id);
        
        if(!$single_book){
            return $this->failure(['message' => 'this particular book is not created by you'], 'You do not have access to this book', 401);
        }
        $single_book->delete();
        return $this->success(['message' => 'book has been banned suessfully']);
    }

    public function restore($id){
        $book = Book::withTrashed($id);
        if(!$book){
            return 'record does not exist';
        }
        return $book;
        $book->restore();
        return $this->success(['info' => 'you can now access this book'],
            'Book Unbanned succesfully');
    }

    public function delete($id){

        $book = Book::find($id);

        if(!$book){
            return $this->failure(['message' => 'record not found']);
        }

        $single_book = $this->getauth()->books()->withTrashed()->find($id);
        
        if(!$single_book){
            return $this->failure(['message' => 'this particular book is not created by you'], 'You do not have access to this book', 401);
        }

       $single_book->forceDelete();
       return $this->success(['info' => 'book deleted successfully']);

    }

    

    

}
