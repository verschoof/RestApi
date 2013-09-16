RestApi
=======

This is a Symfony2 REST Api based on the blog "REST APIs with Symfony2: The Right Way" from William DURAND

If you can improve this bundle for a good example please do a pull request!!


Install
=======

Commandline
git clone git@github.com:verschoof/RestApi.git rest-api

`cp app/config/parameters.dist.yml app/config/parameters_dev.yml`

Edit app/config/parameters_dev.yml to your settings

run `composer install`
run `app/console doctrine:migrations:migrate`

Ensure that mod_rewrite is enabled if you are running Apache and that DocumentRoot is the */rest-api/web path.
Navigate to http://localhost/app_dev.php/v1/test

and done!


Todo
=======
- Create more example functions
