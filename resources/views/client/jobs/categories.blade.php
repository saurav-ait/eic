@extends('admin-master')

@section('content')

<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Category Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- GRID --}}
    <div class="grid">

        {{-- ================= CATEGORY ================= --}}
        <div class="card">

            <div class="card-header">
                <h3>Categories</h3>
                <p>Create and manage job categories</p>
            </div>

            {{-- FORM --}}
            <form action="{{ route('jobs.category.store') }}" method="POST" class="form">
                @csrf

                <input type="text" name="name" placeholder="Category Name" required>

                <textarea name="category_description" placeholder="Description (optional)"></textarea>

                <button class="btn primary">Add Category</button>
            </form>

            {{-- TABLE --}}
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $index => $category)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <strong>{{ $category->name }}</strong>
                            </td>

                            <td>
                                <span class="desc">
                                    {{ $category->category_description ?? '—' }}
                                </span>
                            </td>

                            <td>
                                <form action="{{ route('jobs.category.delete', $category->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete category?')">
                                    @csrf
                                    <button class="btn danger sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="empty">No categories found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        {{-- ================= SUBCATEGORY ================= --}}
        <div class="card">

            <div class="card-header">
                <h3>Subcategories</h3>
                <p>Manage subcategories under categories</p>
            </div>

            {{-- FORM --}}
            <form action="{{ route('jobs.subcategory.store') }}" method="POST" class="form">
                @csrf

                <select name="category_id" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                <input type="text" name="name" placeholder="Subcategory Name" required>

                <textarea name="subcategory_description" placeholder="Description (optional)"></textarea>

                <button class="btn primary">Add Subcategory</button>
            </form>

            {{-- TABLE --}}
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Subcategory</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $sl = 1; @endphp

                        @forelse($categories as $category)
                            @foreach($category->subcategories as $sub)
                            <tr>
                                <td>{{ $sl++ }}</td>

                                <td>
                                    <strong>{{ $sub->name }}</strong>
                                </td>

                                <td>
                                    <span class="tag">{{ $category->name }}</span>
                                </td>

                                <td>
                                    <span class="desc">
                                        {{ $sub->subcategory_description ?? '—' }}
                                    </span>
                                </td>

                                <td>
                                    <form action="{{ route('jobs.subcategory.delete', $sub->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Delete subcategory?')">
                                        @csrf
                                        <button class="btn danger sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        @empty
                        <tr>
                            <td colspan="5" class="empty">No subcategories found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</main>

{{-- ================= STYLES ================= --}}
<style>

/* GRID */
.grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

/* CARD */
.card {
    background:#fff;
    border-radius:12px;
    padding:20px;
    box-shadow:0 6px 16px rgba(0,0,0,0.05);
}

/* HEADER */
.card-header h3 {
    color:#1E4BA6;
    margin-bottom:5px;
}

.card-header p {
    font-size:13px;
    color:#777;
    margin-bottom:15px;
}

/* FORM */
.form {
    display:flex;
    flex-direction:column;
    gap:10px;
    margin-bottom:15px;
}

/* INPUT */
input, textarea, select {
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:13px;
}

textarea {
    min-height:70px;
    resize:vertical;
}

input:focus, textarea:focus, select:focus {
    border-color:#1E4BA6;
    outline:none;
}

/* BUTTON */
.btn {
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:600;
}

.btn.primary {
    background:#1E4BA6;
    color:#fff;
    padding:10px;
}

.btn.primary:hover {
    background:#163a80;
}

.btn.danger {
    background:#e74c3c;
    color:#fff;
}

.btn.danger:hover {
    background:#c0392b;
}

.btn.sm {
    padding:5px 8px;
    font-size:11px;
}

/* TABLE */
.table-wrapper {
    max-height:300px;
    overflow:auto;
}

table {
    width:100%;
    border-collapse:collapse;
}

th {
    background:#1E4BA6;
    color:#fff;
    padding:10px;
    font-size:12px;
    text-align:left;
}

td {
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:12px;
}

/* TEXT */
.desc {
    color:#555;
    font-size:12px;
    line-height:1.4;
}

.tag {
    background:#e3f2fd;
    padding:4px 8px;
    border-radius:20px;
    font-size:11px;
}

/* EMPTY */
.empty {
    text-align:center;
    color:#999;
    padding:15px;
}

/* ALERT */
.alert-success {
    background:#d1fae5;
    color:#065f46;
    padding:12px;
    border-radius:6px;
    text-align:center;
    margin-bottom:15px;
}

/* RESPONSIVE */
@media(max-width:900px){
    .grid {
        grid-template-columns:1fr;
    }
}

</style>

@endsection