@extends('layouts.layout')

@section('content')

<div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
    <div class="mdc-card p-0">
        <h6 class="card-title card-padding pb-0">License Accounting Details</h6>
        <div class="table-responsive">
            <table class="table table-hoverable" style="width:100%;">
                <thead>
                    <tr>
                        <th class="text-left">License Code</th>
                        <th>Price</th>
                        <th>Number of Users</th>
                        <th>Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($access_codes as $access_code)
                    <tr>
                        <td class="text-left">{{ $access_code->code }}</td>
                        <td>{{ $access_code->price }}</td>
                        <td>{{ $access_code->count }}</td>
                        <td>{{ $access_code->count * $access_code->price }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection