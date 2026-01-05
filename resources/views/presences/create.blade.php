@extends('layouts.dashboard')

@section('content')
    <header class="mb-3">
        <a href="#" class="burger-btn d-block d-xl-none">
            <i class="bi bi-justify fs-3"></i>
        </a>
    </header>

    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Presence</h3>
                    <p class="text-subtitle text-muted">Handle Presence data</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Dashboard</a></li>
                            <li class="breadcrumb-item" aria-current="page">Presence</li>
                            <li class="breadcrumb-item active" aria-current="page">New</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        Create
                    </h5>
                </div>
                <div class="card-body">

                     @php
                        $employee = auth()->user()->employee ?? null;
                        $role = $employee?->role?->title;
                    @endphp

                    @if ($role === 'HR')
                    <form action="{{ route('presences.store') }}" method="POST">
                        @csrf
             
                        <div class="mb-3">
                            <label for="" class="form-label">Employee</label>
                                <select name="employee_id" required class="form-control">

                                    @foreach ($employees as $employee )
                                        <option value="{{ $employee->id }}">{{ $employee->fullname }}</option>
                                    @endforeach
                                   
                                </select>
                            @error('employee_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Check In</label>
                            <input type="text" class="form-control datetimehour" name="check_in" required>
                            @error('check_in')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <la5bel for="" class="form-label">Check Out</la5bel>
                            <input type="text" class="form-control datetimehour" name="check_out" required>
                            @error('check_out')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Date</label>
                            <input type="text" class="form-control datetimehour" name="date" required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="" class="form-label">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="present">Present</option>
                                <option value="absent">Absent</option>
                                <option value="leave">Leave</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('presences.index') }}" class="btn btn-secondary">Back to List</a>

                    </form>
                    @else
                    {{-- form mode karyawan --}}
                    <form action="{{ route('presences.store') }}" method="POST">

                        @csrf
                        
                        <div class="mb-3"><b>Note</b> : Mohon izinkan akses lokasi, supaya presensi diterima</div>
                        
                        <div class="mb-3">
                            <label for="" class="form-label">Latitude</label>
                            <input type="text" class="form-control " name="latitude" id="latitude" required>
                        </div>

                        <div class="mb-3">
                            <label for="" class="form-label">Longitude</label>
                            <input type="text" class="form-control " name="longitude" id="longitude" required>
                        </div>

                        <div class="mb-3">
                            <iframe class=""  width="500" height="300"  src="no" frameborder="0" srcdoc=""></iframe>
                        </div>

                        <button type="submit" class="btn btn-primary" id="btn-present" disabled>Present</button>
                    </form>

                    @endif
                </div>
            </div>

        </section>
    </div>

    <script>
        const iframe = document.querySelector('iframe');
        const officeLat = -7.3697672;
        const officeLon = 112.5125893;
        const threshold = 0.01;

        navigator.geolocation.getCurrentPosition(function(position){
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;
            iframe.src = `https://www.google.com/maps?q=${lat},${lon}&output=embed`;
        });

        document.addEventListener('DOMContentLoaded', (event) => {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;

                    document.getElementById('latitude').value = lat;
                    document.getElementById('longitude').value = lon;

                    // compare lokasi sekarang dengan lokasi kantor
                    const distance = Math.sqrt(Math.pow(lat - officeLat, 2) + Math.pow(lon - officeLon, 2));

                    if (distance <= threshold) {
                        alert('Anda berada di kantor, selamat bekerja')
                        document.getElementById('btn-present').removeAttribute('disabled');
                    } else {
                        alert('kamu tidak berada di kantor, pastikan kamu berada di kantor untuk melakukan presensi')
                    }
                });
            }
        });
    </script>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr('.datetime', {
        dateFormat: 'Y-m-d'
    });
    flatpickr('.datetimehour', {
        enableTime: true,
        dateFormat: 'Y-m-d H:i'
    });
</script>
@endpush