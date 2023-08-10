@extends('layouts.dashboard')
@section('content')
<div class="container">
    <div class="table-responsive">
        <table class="table table-bordered" id="livecount">
            <tr>
                <th>Dalam Antrian</th>
                <th>Sedang Diperiksa</th>
                <th>Selesai Diperiksa</th>
            </tr>
            <tr class="tr-satu">

            </tr>
        </table>
    </div>
</div>
@endsection
@push('scripts')
<script>
    function startLiveUpdates() {
        const belumDipanggil = document.getElementById('menunggu');
        const diDalam = document.getElementById('periksa');
        setInterval(function () {
            $('.tr').empty()
            fetch('{{ route('cekantrian') }}').then(function (response) {
                return response.json();
            }).then(function (data) {
                // console.log(data);
                if(data.belum.length == 1){
                   $('table').append(`<tr class="tr"><td class="s1">${data.belum[0].no_antrian} | ${data.belum[0].poli.nama_poli} | ${data.belum[0].dokter.nama}</td></tr>`)
                } else {
                    for (let i = 0; i < data.belum.length; i++) {
                        $('table').append(`<tr class="tr"><td class="s${i+1}">${data.belum[i].no_antrian} | ${data.belum[i].poli.nama_poli} | ${data.belum[i].dokter.nama}</td></tr>`)
                    }
                }
                if(data.didalam.length == 1){
                    $(`<td class="d1">${data.didalam[0].no_antrian} | ${data.didalam[0].poli.nama_poli} | ${data.didalam[0].dokter.nama}</td>`).insertAfter('.s1')
                } else {
                    for (let i = 0; i < data.didalam.length; i++) {
                        $(`<td class="d${i+1}">${data.didalam[i].no_antrian} | ${data.didalam[i].poli.nama_poli} | ${data.didalam[0].dokter.nama}</td>`).insertAfter(`.s${i+1}`)
                    }
                }
                if(data.sudah.length == 1){
                    $(`<td class="t1">${data.sudah[0].no_antrian} | ${data.sudah[0].poli.nama_poli} | ${data.sudah[0].dokter.nama}</td>`).insertAfter('.d1')
                } else {
                    for (let i = 0; i < data.sudah.length; i++) {
                        $(`<td class="t${i+1}">${data.sudah[i].no_antrian} | ${data.sudah[i].poli.nama_poli} | ${data.belum[i].dokter.nama}</td>`).insertAfter(`.d${i+1}`)
                    }
                }
            }).catch(function (error) {
                console.log(error);
            });
        }, 2000);
    }
    document.addEventListener('DOMContentLoaded', function () {
        startLiveUpdates();
    });
</script>
@endpush
