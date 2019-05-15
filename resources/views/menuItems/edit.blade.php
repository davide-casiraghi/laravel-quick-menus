@extends('laravel-quick-menus::menuItems.layout')

@section('javascript-document-ready')
    @parent
    
    {{-- ON LOAD --}}
        hideShowsControls();

    {{-- ON CHANGE --}}
        $("select[name='type']").change(function(){
            hideShowsControls();
         });
         
     {{-- SHOW/HIDE elements relating with the selected menu item TYPE  --}}
         function hideShowsControls(){
             switch($("select[name='type']").val()) {
                 case "1":
                     $(".form-group.url").hide();
                     $(".form-group.route").show();
                     $(".form-group.route").fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                 break;
                 case "2":
                     $(".form-group.route").hide();
                     $(".form-group.url").show();
                     $(".form-group.url").fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                 break; 
                 case "3":
                     $(".form-group.route").hide();
                     $(".form-group.url").hide();
                 break;    
             }
         }
     
@stop


@section('content')    
    <div class="row">
        <div class="col-12 col-sm-6">
            <h2>@lang('menuItem.edit_menu_item')</h2>
        </div>
        <div class="col-12 col-sm-6 text-right">
            <span class="badge badge-secondary">English</span>
        </div>
    </div>

    @include('laravel-quick-menus::partials.error-management', [
      'style' => 'alert-danger',
    ])

    <form action="{{ route('menuItems.update',$menuItem->id) }}" method="POST">
        @csrf
        @method('PUT')

         <div class="row">
            <div class="col-12">
                @include('laravel-quick-menus::partials.input', [
                      'title' => __('menuItem.name'),
                      'name' => 'name',
                      'placeholder' => 'Menu item name',
                      'value' => $menuItem->translate('en')->name,
                      'required' => true,
                ])
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.select', [
                    'title' => __('menuItem.menu_id'),
                    'name' => 'menu_id',
                    'placeholder' => __('menuItem.menu_id'),
                    'records' => $menu,
                    'seleted' => $menuItem->menu_id,
                    'liveSearch' => 'false',
                    'mobileNativeMenu' => true,
                    'required' => true,
                ])
            </div>
            
            <div class="col-12">
                @include('laravel-quick-menus::partials.select-menu-items-parent', [
                    'title' => __('menuItem.parent_menu_item'),
                    'name' => 'parent_item_id',
                    'placeholder' => __('menuItem.parent_menu_item'),
                    'records' => $menuItemsTree,
                    'seleted' => $menuItem->parent_item_id,
                    'liveSearch' => 'false',
                    'mobileNativeMenu' => true,
                    'item_id' => $menuItem->id,
                    'required' => false,
                ])
            </div>
            
            <div class="col-12">
                <div class="form-group">
                    <strong>@lang('menuItem.menu_item_type')</strong>
                    <select name="type" class="selectpicker" title="Route or Url">
                        <option value="1" @if(empty($menuItem->type)) {{'selected'}} @endif @if(!empty($menuItem->type)) {{  $menuItem->type == '1' ? 'selected' : '' }} @endif>Route</option>
                        <option value="2" @if(!empty($menuItem->type)) {{  $menuItem->type == '2' ? 'selected' : '' }} @endif>Url</option>
                        <option value="3" @if(!empty($menuItem->type)) {{  $menuItem->type == '3' ? 'selected' : '' }} @endif>System - User Profile</option>
                        <option value="4" @if(!empty($menuItem->type)) {{  $menuItem->type == '4' ? 'selected' : '' }} @endif>System - Logout</option>    
                    </select>
                </div>
            </div>
            
            <div class="col-12">
                @include('laravel-quick-menus::partials.input', [
                      'title' => __('menuItem.menu_item_route'),
                      'name' => 'route',
                      'placeholder' => 'Route',
                      'value' => $menuItem->route,
                      'required' => false,
                ])
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.input', [
                      'title' => 'Url',
                      'name' => 'url',
                      'placeholder' => 'The relative url - eg: /post/about',
                      'value' => $menuItem->url,
                      'hide' => true,
                      'required' => false,
                ])
            </div>
            <div class="col-12">
                <div class="form-group">
                    <strong>@lang('menuItem.menu_item_access')</strong>
                    <select name="access" class="selectpicker" title="Access">
                        <option value="1" @if(empty($menuItem->access)) {{'selected'}} @endif @if(!empty($menuItem->access)) {{  $menuItem->access == '1' ? 'selected' : '' }} @endif>Public</option>
                        <option value="2" @if(!empty($menuItem->access)) {{  $menuItem->access == '2' ? 'selected' : '' }} @endif>Guest</option>
                        <option value="3" @if(!empty($menuItem->access)) {{  $menuItem->access == '3' ? 'selected' : '' }} @endif>Manager</option>
                        <option value="4" @if(!empty($menuItem->access)) {{  $menuItem->access == '4' ? 'selected' : '' }} @endif>Administrator</option>
                        <option value="5" @if(!empty($menuItem->access)) {{  $menuItem->access == '5' ? 'selected' : '' }} @endif>Super Administrator</option>   
                    </select>
                </div>
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.select-menu-items-order', [
                    'title' => __('menuItem.menu_item_order'),
                    'name' => 'order',
                    'placeholder' => __('menuItem.menu_item_order'),
                    'records' => $menuItemsSameMenuAndLevel,
                    'seleted' => $menuItem->id,
                    'tooltip' => "The menu item will be placed in the menu after the selected menu item.",
                    'liveSearch' => 'false',
                    'mobileNativeMenu' => true,
                    'required' => false,
                ])
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.input', [
                      'title' => __('menuItem.menu_item_font_awesome_class'),
                      'name' => 'font_awesome_class',
                      'placeholder' => __('menuItem.menu_item_font_awesome_class'),
                      'value' => $menuItem->font_awesome_class,
                      'required' => false,
                ])
            </div>
            <div class="col-12">
                @include('laravel-quick-menus::partials.checkbox', [
                      'name' => 'hide_name',
                      'description' => __('menuItem.menu_item_hide_name'),
                      'value' => $menuItem->hide_name,
                      'required' => false,
                ])
            </div>
        </div>

        @include('laravel-quick-menus::partials.buttons-back-submit', [
            'route' => 'menuItemsIndex',
            'routeParameter'  => $menuItem->menu_id,
        ])

    </form>

@endsection
