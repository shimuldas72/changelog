## About Changelog package
This package is responsible for logging create, update and delete event in a table and gives the admin a view of what is happening in this system. 

Installtaion:

    $ composer require sdas/changelog
    
After installing the package publish the config file

    $ php artisan vendor:publish --tag=changelog-config

Published config file will be found in the config folder of your laravel project. Update the config variables according to your project structure.

Now, run the command below to refresh all the cache

    $ php artisan optimize
    
Great, installation is done.

Now use <b>Sdas\Changelog\Http\Traits\Trackable</b> trait in any class that extends <b>Illuminate\Database\Eloquent\Model</b> like this

    <?php
    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;
    use Sdas\Changelog\Http\Traits\Trackable;
    class Democlass extends Model
    {
        use Trackable;
    }
    
Now this package will start to log changes of the table which is represented by your model where you used the <b>Trackable</b> trait.
