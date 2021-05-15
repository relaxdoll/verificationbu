<nav class="mobile-bottom-nav" v-show="show">
    <div class="row">
        <div @click="selectTab('purchase')" class="col-4" style="text-align: center;">
            <a :class="iconClass('purchase')"></a>
        </div>
        <div @click="selectTab('create')" class="col-4" style="text-align: center;">
            <a :class="iconClass('create')"></a>
        </div>
        <div @click="selectTab('product')" class="col-4" style="text-align: center;">
            <a :class="iconClass('product')"></a>
        </div>

        <div @click="selectTab('purchase')" class="col-4 icon-text">
            <p :class="labelClass('purchase')">Purchase</p>
        </div>
        <div @click="selectTab('create')" class="col-4 icon-text">
            <p :class="labelClass('create')">Create</p>
        </div>
        <div @click="selectTab('product')" class="col-4 icon-text">
            <p :class="labelClass('product')">My QR</p>
        </div>
    </div>
</nav>
