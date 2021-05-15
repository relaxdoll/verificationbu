<div class="wrapper ">
    @include('layouts.navbars.sidebar')
    {{--    https://codepen.io/M-Jao/pen/VRjOZO--}}
    <div class="main-panel" :data="'blue'">
        @include('layouts.navbars.navs.auth')

        {{--        <div class="se-pre-con"><div class="lds-default"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div> </div>--}}

        {{--        <loader></loader>--}}

        <div class="content">

            @include('layouts.loader')

            @yield('content')

        </div>
        @include('layouts.footers.auth')
    </div>
</div>
