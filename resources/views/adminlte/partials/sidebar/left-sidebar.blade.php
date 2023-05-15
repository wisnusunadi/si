<aside class="main-sidebar {{ config('adminlte.classes_sidebar2', 'sidebar-dark-primary royal-bg elevation-4') }}">
    {{-- Sidebar brand logo --}}
    @if (config('adminlte.logo_img_xl'))
        @include('adminlte.partials.common.brand-logo-xl')
    @else
        @include('adminlte.partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar" style="margin-top: 57px;">

        {{-- User Profile --}}
        <div class="card mb-3 user-panel-bg" id="user-panel-profil" style="max-width: 540px; margin-top:20px;">
            <div class="row no-gutters" id="user-image">
                <div class="col-md-4" style="margin:auto; text-align:center; padding-left:10px;">
                    @if (isset(Auth::user()->foto) && Auth::user()->foto != null)
                        <form method="post">
                            <label for="upload_image">
                                <img src="{{ url('assets/image/user') }}/{{ Auth::user()->foto }}"
                                    class="karyawan-img-sm-md circle-button">
                                <div class="overlay">
                                    <div class="text">Click to Change Profile Image</div>
                                </div>
                                <input type="file" name="image" class="image" id="upload_image"
                                    style="display:none" />
                            </label>
                        </form>
                    @else
                        <img src="{{ url('assets/image/user') }}/unknown.png" class="karyawan-img-sm-md circle-button">
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title font-weight-bold">
                            @if (isset(Auth::user()->karyawan->nama))
                                @php echo substr_replace( Auth::user()->karyawan->nama, "...", 20)@endphp
                            @endif
                        </h5>
                        <p class="card-text">
                            @if (isset(Auth::user()->karyawan->nama))
                                {{ Auth::user()->karyawan->divisi->nama }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="hide">I am shown when someone hovers over the div above.</div>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if (config('adminlte.sidebar_nav_animation_speed') != 300) data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}" @endif
                @if (!config('adminlte.sidebar_nav_accordion')) data-accordion="false" @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte.partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>


<div class="modal fade" id="profileImageModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
    aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Ubah Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <div class="row">
                        <div class="col-md-8" style="max-height: 300px !important;max-width: 300px !important;">
                            <img src="" id="sample_image" />
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="preview d-flex justify-content-center pt-3"></div>
                        </div> --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <button type="button" id="update_photo_profile"
                    class="btn btn-primary st-cropper-upload-btn btn-md">Ubah
                    <i class="fa fa-spinner fa-spin showloading"
                        style="color:white; font-size:12px; display:none;  position:absolute; margin-left: 3px; margin-top: 4px;"></i>
                </button>
                <button type="button" class="btn btn-secondary btn-md st-cropper-close-btn"
                    data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
