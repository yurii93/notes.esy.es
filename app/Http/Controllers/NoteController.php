<?php

namespace App\Http\Controllers;

use App\Author;
use App\Note;
use Illuminate\Http\Request;

use App\Http\Requests;

class NoteController extends Controller
{
    public function getIndex($author = null)
    {

        if(!is_null($author)) {

            $noteAuthor = Author::where('name', $author)->first();

            if($noteAuthor) {

                $notes = $noteAuthor->notes()->orderBy('created_at', 'desc')->paginate(6);
            }
        } else {

            $notes = Note::orderBy('created_at', 'desc')->paginate(6);
        }

        return view('index', ['notes' => $notes]);
    }

    public function postNote(Request $request)
    {
        $this->validate($request,[
            'author' => 'required|max:60|alpha',
            'note' => 'required|max:500'
        ]);

        $authorField = ucfirst($request['author']);
        $noteField = $request['note'];

        $author = Author::where('name', '=',$authorField)->first();

        if(!$author) {

            $author = new Author();
            $author->name = $authorField;
            $author->save();
        }

        $note = new Note();

        $note->note = $noteField;
        $author->notes()->save($note);

        return redirect()->route('index')->with(['success' => 'Note saved!']);
    }

    public function getDeleteNote($note_id)
    {
        $note = Note::find($note_id);

        $delete_author = false;

        if(count($note->author->notes) === 1) {

            $note->author->delete();
            $delete_author = true;
        }

        $note->delete();

        $message = $delete_author ? 'Note and author deleted!' : 'Note deleted!';

        return redirect()->route('index')->with(['success' => $message]);
    }
}
