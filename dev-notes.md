
update to rappasoft/livewire-tables v3 beta 11 - publishing views adds "rappasoft" directory in vendor.
previously published under vendor/livewire-tables. must remove "rappsoft" to use published views.  

update to laravel/livewire v3.0.6 causes Route [livewire.update] not defined
corrected with addition of:  
Livewire::setUpdateRoute(function ($handle) {
return Route::name('livewire.update')
->post('/livewire/update', $handle)
->middleware(['web', 'auth.admin']);
});  
to appserviceprovider boot() method.  


~~Illuminate\Contracts\Validation\Rule; deprecated in laravel 10
see Illuminate\Contracts\ValidationRule;
affects app/Modules/Settings/Rules/ValidFile.php~~
----------------------------
Chrome (as of Version 100.0.4896.127) does not allow mouse wheel scrolling in time input controls.
It works in Firefox.
------------------
~~known issue with alpha admin-lte-v4 setting sidebar to collapsed mode in system settings.~~

--------
~~known issue dompdf fail with text containing < (less than character)  
(  1234<wdrwer  in item description will fail pdf)~~

------------
-------------------------------
Routes note laravel undocumented  https://github.com/laravel/framework/issues/19020  
when loading custom routes in AppServiceProvider, the name() method in the loaded routes.php  
has to be first or it does not get initialized with the route properly  
WORKS  
Route::name('index')->get('/', 'Controller@index');  
DOES NOT WORK  
Route::get('/', 'Controller@index')->name('index');
---------------------------------------


