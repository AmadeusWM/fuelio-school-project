<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// thus we disable autorouting for safety [https://www.codeigniter.com/user_guide/incoming/routing.html#use-defined-routes-only]
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('success', 'Pages::successPage');
$routes->get('failure', 'Pages::failurePage');

// groups prepend "account" before every route (https://codeigniter.com/user_guide/incoming/routing.html#redirecting-routes)
$routes->group('account', static function ($routes) {
    $routes->get('overview/profile', 'User\Account\ProfileController::index');
    $routes->addRedirect('overview', 'account/overview/profile');
    $routes->get('overview/orders', 'User\Account\OrdersController::index');
    $routes->get('overview/products', 'User\Account\ProductsController::index');
    $routes->get('overview/analytics', 'User\Account\AnalyticsController::index');
    $routes->get('overview/products/addProduct', 'User\Account\ProductsController::addProductPage');
    $routes->get('overview/products/editProduct/(:num)', 'User\Account\ProductsController::editProductPage/$1');

    $routes->post('ProfileController/updateProfile', 'User\Account\ProfileController::updateProfile');
    $routes->post('ProfileController/removeImage', 'User\Account\ProfileController::removeImage');

    $routes->post('ProductsController/addProduct', 'User\Account\ProductsController::addProduct');
    $routes->post('ProductsController/removeProduct', 'User\Account\ProductsController::removeProduct');
    $routes->post('ProductsController/removeFile', 'User\Account\ProductsController::removeFile');

    $routes->post('OrdersController/orderDelivered/(:num)', 'User\Account\OrdersController::orderDelivered/$1');
    $routes->post('OrdersController/orderCanceled/(:num)', 'User\Account\OrdersController::orderCanceled/$1');
});

$routes->group("store", static function ($routes) {
    $routes->get('search', 'Store\ProductSearchController::search');
    $routes->get('search/(:num)', 'Store\ProductSearchController::search/$1');
$routes->get('product/(:num)', 'Store\ProductController::index/$1');
    $routes->get('webshop/(:num)', 'Store\WebshopController::index/$1');
    $routes->post('product/addReview/(:num)', 'Store\ReviewController::addReview/$1');
    $routes->post('product/deleteReview/(:num)', 'Store\ReviewController::deleteReview/$1');
});
$routes->group("cart", static function ($routes) {
    $routes->post('addProductToCart', 'Cart\OrderController::addProductToCart');
    $routes->post('removeProductFromCart', 'Cart\OrderController::removeProductFromCart');
    $routes->get('cart', 'Cart\OrderController::cartPage');
    $routes->get('checkout', 'Cart\OrderController::checkoutPage');
    $routes->get('placeOrder', 'Cart\OrderController::placeOrder');
});

$routes->group("message", static function ($routes) {
    $routes->get('(:num)', 'Messaging\MessagingController::messageSenderPage/$1');
    $routes->post('messageUser/(:num)', 'Messaging\MessagingController::messageUser/$1');
    $routes->post('notifyLater/(:num)', 'Messaging\MessagingController::notifyLater/$1');
});

$routes->get('login', 'User\SignInController::index');
$routes->get('register', 'User\SignUpController::index');

$routes->group('SignInController', static function ($routes) {
    $routes->post('login', 'User\SignInController::login');
    $routes->get('logout', 'User\SignInController::logout');
});

$routes->post('SignUpController/register', 'User\SignUpController::register');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
