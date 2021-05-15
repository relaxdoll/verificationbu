<div class="navbar-minimize-fixed">
    <button @click="$store.commit('navBarToggle')" class="minimize-sidebar btn btn-link btn-just-icon" id="minimizeSidebar2">
        <i class="tim-icons icon-align-center visible-on-sidebar-regular text-muted"></i>
        <i class="tim-icons icon-bullet-list-67 visible-on-sidebar-mini text-muted"></i>
    </button>
</div>
<div class="sidebar" data="blue">

    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="/" class="simple-text logo-mini">
                <img src="/img/logo.png" alt="app-logo"/>
            </a>
            <a href="/" class="simple-text logo-normal">
                {{ __('ONEIBIS') }}
            </a>
        </div>
        <ul class="nav">

            <side title="Info" icon="eec-icons icon-chart-pie-36-2" href="{{ route('info.index') }}" page="indexDriver" activepage="{{ $activePage }}"></side>

            <side title="Tracking No" icon="eec-icons icon-tags-stack-2" href="{{ route('tracking.index') }}" page="indexVehicle" activepage="{{ $activePage }}"></side>

            <side title="Movement" icon="eec-icons icon-distance-2" href="{{ route('movement.index') }}" page="indexCustomer" activepage="{{ $activePage }}"></side>


        </ul>
    </div>
</div>


{{--            <li class="nav-item {{ ($activePage == 'createInventory' || $activePage == 'indexInventory') ? ' active' : '' }}">--}}
{{--                <a class="nav-link {{ ($activePage == 'createInventory' || $activePage == 'indexInventory') ? '' : 'collapse' }}" data-toggle="collapse"--}}
{{--                   href="#InventoryNav"--}}
{{--                   aria-expanded="{{ ($activePage == 'createInventory' || $activePage == 'indexInventory') ? 'true' : 'false' }}">--}}
{{--                    <i class="material-icons">store</i>--}}
{{--                    <p>{{ __('Inventory') }}--}}
{{--                        <b class="caret"></b>--}}
{{--                    </p>--}}
{{--                </a>--}}
{{--                <div class="collapse {{ ($activePage == 'createInventory' || $activePage == 'indexInventory') ? 'show' : '' }}" id="InventoryNav">--}}
{{--                    <ul class="nav">--}}

{{--                        <li class="nav-item{{ $activePage == 'indexInventory' ? ' active' : '' }}">--}}
{{--                            <a class="nav-link" href="{{ route('inventories.index') }}">--}}
{{--                                <i style="margin-top: 2px;" class="material-icons">library_books</i>--}}
{{--                                <span class="sidebar-normal">{{ __('Inventory') }} </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}

{{--                        <li class="nav-item{{ $activePage == 'createInventory' ? ' active' : '' }}">--}}
{{--                            <a class="nav-link" href="{{ route('inventories.create') }}">--}}
{{--                                <i style="margin-top: 2px;" class="material-icons">add</i>--}}
{{--                                <span class="sidebar-normal">{{ __('Add Inventory') }} </span>--}}
{{--                            </a>--}}
{{--                        </li>--}}

{{--                    </ul>--}}
{{--                </div>--}}
{{--            </li>--}}
