-------------------------------
Routes note laravel undocumented  https://github.com/laravel/framework/issues/19020  
when loading custom routes in AppServiceProvider, the name() method in the loaded routes.php  
has to be first or it does not get initialized with the route properly  
WORKS  
         Route::name('index')->get('/', 'Controller@index');  
DOES NOT WORK  
         Route::get('/', 'Controller@index')->name('index');  
---------------------------------------
