@extends('adminlte.page')

@section('content_header')
<h1 class="m-0 text-dark">Perencanaan Perakitan Bulan <span class="date"></span></h1>
@endsection
@section('content')
    <div id="app"></div>
@endsection
@section('adminlte_js')
    <script src="{{ asset('native/js/direksi.js') }}"></script>
    <script>
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
        "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        const d = new Date();
        const month = monthNames[d.getMonth() + 1];
        const year = d.getFullYear();
        $('.date').text(month + ' ' + year);
    </script>
@endsection
