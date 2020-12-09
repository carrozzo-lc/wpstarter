# WordPress local development starter

####  Local site settings
------------
Choose a name for your dev local site and in your hosts file add the following line.

    127.0.0.1    dev.yoursite.localhost

####  Docker configuration
------------
Make sure you have the latest versions of **Docker** and **Docker Compose** installed on your machine.

Open a terminal and `cd` to the folder `docker-wps` where are all docker files.

###### Configuration

Edit the `.env` file to change the default IP address, MySQL root password and WordPress database name.

###### Installation
 run:
```
docker-compose up
```
This creates two new folders.
* `wp-data` – used to store and restore database dumps
* `webroot` – the location of your WordPress application

For an existing site, you can add the database dump on the wp-data folder to restore and use an existing database. For the WordPress files just copy all content that you wish to reuse (themes, plugins, uploads, etc...) on the ./webroot/wp-content folder.

The containers are now built and running. You should be able to access the WordPress installation with the configured IP in the browser address. By default it is `http://dev.yoursite.localhost`.

###### Usage

**Starting containers**

You can start the containers with the `up` command in daemon mode (by adding `-d` as an argument) or by using the `start` command:
```
docker-compose start
```

**Stopping containers**

```
docker-compose stop
```

**Removing containers**

To stop and remove all the containers use the`down` command:

```
docker-compose down
```
Use `-v` if you need to remove the database volume which is used to persist the database:
```
docker-compose down -v
```

####  WordPress configuration
------------
If you use a child theme add the following line to the functions.php file to connect css and js files.

```
function your_child_enqueue_scripts() {
	wp_enqueue_style( 'your-child-style', get_stylesheet_uri() );
    wp_enqueue_style( 'your-child-main', get_stylesheet_directory_uri(). '/css/main.css' );

    wp_enqueue_script('your-child-script', get_stylesheet_directory_uri(). '/js/app.min.js', array('jquery'), false, true);
}
add_action( 'wp_enqueue_scripts', 'your_child_enqueue_scripts', 20 );
```

If you use a standard/parent theme.

```
function your_theme_scripts() {
    wp_enqueue_style( 'your_theme-main-style', get_template_directory_uri(). '/css/main.css' );
    wp_enqueue_script('your_theme-script', get_template_directory_uri(). '/js/app.min.js', array('jquery'), false, true);    
}
add_action( 'wp_enqueue_scripts', 'your_theme_scripts' );
```

####  Gulp configuration
------------
If you don't have Gulp install following the installation guide on the official website.
https://gulpjs.com/

In the root folder open the gulpfile.js and change all theme paths from:
```
./webroot/wp-content/themes/YOUR_THEME_FOLDER
```
with your theme folder name.

After that, open a terminal and `cd` to the root folder:
- run `npm install`
- then run `gulp`

After Gulp is running add ":3000" after the local url like http://dev.yoursite.localhost:3000 it's for Browsersync to correctly refresh the page after any changes in you gulp watched files.

####  Done! Now you can work and develop on your local WorPress Site.