Chrome (as of Version 100.0.4896.127) does not allow mouse wheel scrolling in time input controls.
It works in Firefox.
------------------
~~known issue with alpha admin-lte-v4 setting sidebar to collapsed mode in system settings.~~

--------
known issue dompdf fail with text containing < (less than character)  
(  1234<wdrwer  in item description will fail pdf)

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


