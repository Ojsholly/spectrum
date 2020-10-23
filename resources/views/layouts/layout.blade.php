<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Spectrum Books</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
  integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<link rel="stylesheet" href="{{ asset('css/material.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/dataTables.material.min.css') }}">
  <!-- plugins:css -->

  <link rel="stylesheet" href="{{ asset('vendors/mdi/css/materialdesignicons.min.css')}}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css')}}">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="{{ asset('vendors/flag-icon-css/css/flag-icon.min.css')}}">
  <!-- End plugin css for this page -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="{{ asset('css/demo/style.css')}}">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="{{ asset('images/Spectrum logo.png')}}" />


  <!-- plugins:js -->
  <script src="{{ asset('vendors/js/vendor.bundle.base.js')}}" async></script>

<style>
    table{
        width:100%;
    }

</style>
</head>
<body>
<script src="{{ asset('js/preloader.js')}}"></script>
  <div class="body-wrapper" id="app">
    @if(Session::has('success'))
      <flash-success message="{{ Session::get('success') }} "></flash-success>
    @endif
    <!-- partial -->



      <!-- dashboard -->

      <div class="page-wrapper mdc-toolbar-fixed-adjust">
        <!-- Sidebar -->
        <vue-progress-bar></vue-progress-bar>
        @include('layouts.partials.sidebar')
        <div class="main-wrapper mdc-drawer-app-content">
        <!-- Header -->
        @include('layouts.partials.header')

        <!-- Page Content -->
        @yield('content')
      </div>
    </div>
  </div>

  <script src="{{ asset('js/app.js')}}"></script>

  <script src="{{ asset('js/material.js')}}"></script>
  <script src="{{ asset('js/misc.js')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('js/dashboard.js')}}"></script>
  <!-- End custom js for this page-->#
{{-- <script src="{{ asset('js/dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.material.min.js') }}"></script> --}}
  
  
  <script>

</script>
  <script>
    $(document).ready(function() {
      
      // $('table').DataTable( {
      //     columnDefs: [
      //         {
      //           targets: [ 0, 1, 2 ],
      //           className: 'mdl-data-table__cell--non-numeric'
      //         }
      //     ]
      // } );
  } );
  </script>
</body>
</html>
