<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ใบเตือนออนไลน์</title>
 
    <!-- Scripts -->
    {{-- <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script> --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/moment.js') }}"></script>
    <script src="{{ asset('js/jquery-confirm.min.js') }}"></script>

    <!-- JQX -->
    {{-- <script type="text/javascript" src="/jqx/jqx-all.js"></script> --}}

    
    <script type="text/javascript" src="/jqx/jqxcore.js"></script>
    <script type="text/javascript" src="/jqx/jqxdata.js"></script>
    <script type="text/javascript" src="/jqx/jqxgrid.js"></script>
    <script type="text/javascript" src="/jqx/jqxgrid.pager.js"></script>
    <script type="text/javascript" src="/jqx/jqxgrid.filter.js"></script>
    <script type="text/javascript" src="/jqx/jqxgrid.columnsresize.js"></script>
    <script type="text/javascript" src="/jqx/jqxgrid.selection.js"></script>
    <script type="text/javascript" src="/jqx/jqxmenu.js"></script>
    <script type="text/javascript" src="/jqx/jqxdatetimeinput.js"></script>
    <script type="text/javascript" src="/jqx/jqxcalendar.js"></script>
    <script type="text/javascript" src="/jqx/jqxtooltip.js"></script>
    <script type="text/javascript" src="/jqx/jqxbuttons.js"></script>
    <script type="text/javascript" src="/jqx/jqxscrollbar.js"></script>
    <script type="text/javascript" src="/jqx/jqxlistbox.js"></script>
    <script type="text/javascript" src="/jqx/jqxdropdownlist.js"></script>
    <script type="text/javascript" src="/jqx/jqxcheckbox.js"></script>
    <script type="text/javascript" src="/jqx/jqxgrid.edit.js"></script>
    <script type="text/javascript" src="/jqx/globalization/globalize.js"></script>
    <script type="text/javascript" src="/jqx/globalization/globalize.culture.th-TH.js"></script>
    



    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/well.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <!-- JqxWidget -->
    <link rel="stylesheet" href="/jqx/styles/jqx.base.css" type="text/css" />
    <link rel="stylesheet" href="/jqx/styles/jqx.energyblue.css" type="text/css" />
    
    


    

</head>
    <body>
        <!-- Include Navbar -->
        @include('inc.navbar')

        <main class="py-4">
            {{-- <div class="container"> --}}
                <!-- Include Message Style -->
                {{-- @include('inc.mes') --}}

                <!-- Insert content from other page-->
                @yield('content')

            {{-- </div> --}}
        </main>

    </body>

</html>


