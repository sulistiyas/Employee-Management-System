@extends('layouts.app')

@section('title', 'Persetujuan Cuti - Director')

@section('content')
    @include('leave-requests._index', [
        'leaveRequests' => $leaveRequests,
        'rolePrefix' => 'director',
        'pageTitle' => 'Persetujuan Cuti Final',
        'pageSubtitle' => 'Pengajuan cuti yang sudah disetujui Manager & HR, menunggu persetujuan final Anda.',
    ])
@endsection