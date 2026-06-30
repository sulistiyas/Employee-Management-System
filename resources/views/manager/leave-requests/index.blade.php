@extends('layouts.app')

@section('title', 'Persetujuan Cuti - Manager')

@section('content')
    @include('leave-requests._index', [
        'leaveRequests' => $leaveRequests,
        'rolePrefix' => 'manager',
        'pageTitle' => 'Persetujuan Cuti',
        'pageSubtitle' => 'Pengajuan cuti dari tim Anda yang menunggu persetujuan.',
    ])
@endsection