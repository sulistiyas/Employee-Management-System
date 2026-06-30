@extends('layouts.app')

@section('title', 'Persetujuan Cuti - HR')

@section('content')
    @include('leave-requests._index', [
        'leaveRequests' => $leaveRequests,
        'rolePrefix' => 'hr',
        'pageTitle' => 'Persetujuan Cuti',
        'pageSubtitle' => 'Pengajuan cuti yang sudah disetujui Manager, menunggu persetujuan HR.',
    ])
@endsection