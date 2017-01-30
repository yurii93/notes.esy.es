@extends('layouts.master')

@section('title')
    Notes
@endsection

@section('styles')
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    @if(!empty(Request::segment(1)))
        <section class="filter-bar">
            A filter has been set! <a href="{{ route('index') }}">Show all notes</a>
        </section>
    @endif
    @if(count($errors) > 0)
        <section class="info-box fail">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </section>
    @endif
    @if(Session::has('success'))
        <section class="info-box success">
            {{ Session::get('success') }}
        </section>
    @endif
    @if(count($notes) > 0)
        <section class="notes">
            <h1>Latest Notes</h1>
            @for($i = 0; $i < count($notes); $i++)
                <article
                        class="note{{--{{ $i % 3 === 0 ? ' first-in-line' : (($i + 1) % 3 === 0 ? ' last-in-line' : '' )}}--}}">
                    <div class="delete"><a href="{{ route('delete', ['note_id' => $notes[$i]->id]) }}">x</a></div>
                    {{ $notes[$i]->note }}
                    <div class="info">Crated by <a href="{{ route('index', ['author' => $notes[$i]->author->name]) }}">{{ $notes[$i]->author->name }}</a> on {{ $notes[$i]->created_at }}</div>
                </article>
            @endfor
            <div class="pagination">
                @if($notes->currentPage() !== 1)
                    <a href="{{ $notes->previousPageUrl() }}"><span class="fa fa-caret-left"></span></a>
                @endif
                @if($notes->currentPage() !== $notes->lastPage() && $notes->hasPages())
                    <a href="{{ $notes->nextPageUrl() }}"><span class="fa fa-caret-right"></span></a>
                @endif
            </div>
        </section>
    @else
        <div class="no-notes">There are no notes.</div>
    @endif
    <section class="add_note">
        <h1>Add Note</h1>
        <form action="{{ route('create') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="input-group">
                <label for="name">Your name</label>
                <input id="name" type="text" name="author" placeholder="Name">
            </div>
            <div class="input-group">
                <label for="note">Your note</label>
                <textarea id="note" name="note" rows="5" placeholder="Note"></textarea>
            </div>
            <button type="submit" class="btn">Submit note</button>
        </form>
    </section>
<script>

    setTimeout("$('.info-box').fadeOut(2000);", 7000);

</script>
@endsection

