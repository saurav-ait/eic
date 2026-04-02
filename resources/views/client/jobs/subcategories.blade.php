@extends('admin-master')

@section('content')
<main class="main-content">

    <h2>Subcategories</h2>

    {{-- ADD SUBCATEGORY --}}
    <form method="POST" action="{{ route('jobs.subcategory.store') }}">
        @csrf

        <select name="category_id" required>
            <option value="">Select Category</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <input type="text" name="name" placeholder="Subcategory Name" required>

        <button class="btn-primary">Add Subcategory</button>
    </form>

    <br>

    {{-- LIST --}}
    <table border="1" width="100%" cellpadding="10">
        <tr>
            <th>#</th>
            <th>Subcategory</th>
            <th>Category</th>
            <th>Action</th>
        </tr>

        @php $sl=1; @endphp

        @foreach($categories as $cat)
            @foreach($cat->subcategories as $sub)
            <tr>
                <td>{{ $sl++ }}</td>
                <td>{{ $sub->name }}</td>
                <td>{{ $cat->name }}</td>

                <td>
                    <form action="{{ route('jobs.subcategory.delete', $sub->id) }}" method="POST">
                        @csrf
                        <button style="background:red;color:white;">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        @endforeach

    </table>

</main>
@endsection