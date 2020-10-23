@extends('layouts.layout')

@section('content')
<main class="content-wrapper">
    <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
        <div class="mdc-card p-0">
            <h6 class="card-title card-padding pb-0">Restore Deleted Books </h6>
            @isset($books)
            @empty($book)
            <p class="text-center">No Deleted Books Yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-hoverable">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th class="text-left">Book Name</th>
                            <th>Book Author</th>
                            <th>Book Publisher</th>
                            <th>Date Published</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($count = 0)
                        @foreach ($books as $book)
                        @php(++$count)
                        <tr>
                            <td>{{ $count }}</td>
                            <td class="text-left">{{ $book->title }}</td>
                            <td>{{ $book->author }}</td>
                            <td>{{ $book->publisher }}</td>
                            <td>{{ date('Y-m-d', strtotime($book->date_published)) }}</td>
                            <td>
                                <a href="{{ url('admin/books/restore-book/'.$book->id) }}"
                                    class="mdc-button mdc-button--raised icon-button filled-button--primary">
                                    <i class="material-icons mdc-button__icon">visibility</i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endempty
            @else
            <p class="text-center">No Deleted Books Yet.</p>
            @endisset
        </div>
    </div>
</main>
@endsection