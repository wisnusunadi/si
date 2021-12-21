@extends('adminlte.master')

@inject('LayoutHelper', 'App\Helpers\LayoutHelper')

@section('master_css')
<link rel="stylesheet" href="{{asset('assets/css/table.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/text.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/image.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/calendar.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/background.css')}}">
<link rel="stylesheet" href="{{asset('assets/css/button.css')}}">
<style>
    #loader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 10000;
        background-color: #000;
    }

    #loading-gif {
        display: block;
        position: relative;
        left: 50%;
        top: 50%;
        width: 150px;
        height: 150px;
        margin: -75px 0 0 -75px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #3489db;
        animation: spin 2s linear infinite;
        z-index: 10001;
    }

    #loading-gif:before {
        content: "";
        position: absolute;
        left: 5px;
        top: 5px;
        right: 5px;
        bottom: 5px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #f9c922;
        animation: spin 3s linear infinite;
    }

    #loading-gif:after {
        content: "";
        position: absolute;
        left: 15px;
        top: 15px;
        right: 15px;
        bottom: 15px;
        border-radius: 50%;
        border: 3px solid transparent;
        border-top-color: #e74c3c;
        animation: spin 1.5s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
@yield('adminlte_css')
@stop

@include('sweetalert::alert')

@section('classes_body', $LayoutHelper->makeBodyClasses())

@section('body_data', $LayoutHelper->makeBodyData())

@section('body')
<div id="loader">
    <div id="loading-gif"></div>
</div>

<div class="layout-navbar-fixed layout-fixed">
    <div class="wrapper">

        {{-- Top Navbar --}}
        @if($LayoutHelper->isLayoutTopnavEnabled())
        @include('adminlte.partials.navbar.navbar-layout-topnav')
        @else
        @include('adminlte.partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$LayoutHelper->isLayoutTopnavEnabled())
        @include('adminlte.partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        <div id="App" class="content-wrapper {{ config('adminlte.classes_content_wrapper') ?? '' }}">

            {{-- Content Header --}}
            <div class="content-header">
                <div class="container-fluid">
                    @yield('content_header')
                </div>
            </div>

            {{-- Main Content --}}
            <div class="content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

        </div>

        {{-- Footer --}}
        @hasSection('footer')
        @include('adminlte.partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
        @include('adminlte.partials.sidebar.right-sidebar')
        @endif

    </div>
</div>
@stop

@section('master_js')
<script>
        // Restricts input for the given textbox to the given inputFilter.
        // Restricts input for the given textbox to the given inputFilter.
(function($) {
  $.fn.inputFilter = function(inputFilter) {
    return this.on("input keydown keyup mousedown mouseup select contextmenu drop", function() {
      if (inputFilter(this.value)) {
        this.oldValue = this.value;
        this.oldSelectionStart = this.selectionStart;
        this.oldSelectionEnd = this.selectionEnd;
      } else if (this.hasOwnProperty("oldValue")) {
        this.value = this.oldValue;
        this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
      } else {
        this.value = "";
      }
    });
  };
}(jQuery));
    console.log('preparing...');

    $(document).ready(function() {
        console.log('ready...');

        $(".number").inputFilter(function(value) {
            return /^\d*$/.test(value);    // Allow digits only, using a RegExp
        });
        setTimeout(function() {
            $('#loader').fadeOut();
        }, 100);


        $('.select2s').select2({
            placeholder: "Pilih Data",
            allowClear: true
        });

        OverlayScrollbars(document.getElementsByTagName('body'), {
            className: "os-theme-dark",
            resize: "both",
            sizeAutoCapable: true,
            paddingAbsolute: true,
            scrollbars: {
                clickScrolling: true
            }
        })

        var flag = 0;
        $('[data-widget="pushmenu"]').click(function() {
            if (flag == 0) {
                $('#user-image').hide();
                $('#search-widget').hide();
                flag = 1;
                if (window.innerWidth > 992) {
                    $('.layout-navbar-fixed .wrapper .brand-link').addClass('mini');
                }
            } else if (flag == 1) {
                $('#user-image').show();
                $('#search-widget').show();
                flag = 0;
                $('.layout-navbar-fixed .wrapper .brand-link.mini').removeClass('mini');
            }
        });

        $(window).resize(function() {
            flag = 0;
        });
    });
</script>
@yield('adminlte_js')
@stop
