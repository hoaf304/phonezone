<?php
// ============================================================
// PHONEZONE - Routes
// Khai báo tất cả URL → Controller@method
// ============================================================

// ============================================================
// FRONTEND ROUTES
// ============================================================

// Trang chủ
$router->get('/',              'HomeController@index');
$router->get('/tim-kiem',      'HomeController@search');

// Sản phẩm
$router->get('/san-pham',               'ProductController@list');
$router->get('/san-pham/:slug',         'ProductController@detail');
$router->get('/danh-muc/:slug',         'ProductController@byCategory');
$router->get('/hang/:slug',             'ProductController@byBrand');
$router->get('/so-sanh',                'ProductController@compare');
$router->post('/so-sanh/them',          'ProductController@addCompare');
$router->get('/so-sanh/xoa-het',        'ProductController@clearCompare');
$router->get('/so-sanh/xoa/:id',        'ProductController@removeCompare');

// Giỏ hàng
$router->get('/gio-hang',               'CartController@index');
$router->post('/gio-hang/them',         'CartController@add');
$router->post('/gio-hang/cap-nhat',     'CartController@update');
$router->get('/gio-hang/xoa/:id',       'CartController@remove');
$router->get('/gio-hang/xoa-het',       'CartController@clear');
$router->post('/gio-hang/kiem-tra-ma',  'CartController@applyCoupon');
$router->get('/gio-hang/xoa-ma',        'CartController@removeCoupon');

// Đặt hàng
$router->get('/dat-hang',               'OrderController@checkout');
$router->post('/dat-hang/xu-ly',        'OrderController@process');
$router->get('/dat-hang/thanh-cong',    'OrderController@success');
$router->get('/don-hang/:code',         'OrderController@trackOrder');

// Đánh giá
$router->post('/danh-gia',              'ReviewController@store');

// Auth - Khách hàng
$router->get('/dang-nhap',              'AuthController@loginForm');
$router->post('/dang-nhap',             'AuthController@login');
$router->get('/dang-ky',               'AuthController@registerForm');
$router->post('/dang-ky',              'AuthController@register');
$router->get('/dang-xuat',              'AuthController@logout');
$router->get('/quen-mat-khau',          'AuthController@forgotForm');
$router->post('/quen-mat-khau',         'AuthController@forgot');

// Tài khoản khách hàng
$router->get('/tai-khoan',              'AccountController@index');
$router->get('/tai-khoan/don-hang',     'AccountController@orders');
$router->get('/tai-khoan/yeu-thich',    'AccountController@wishlist');
$router->post('/tai-khoan/cap-nhat',    'AccountController@update');

// Yêu thích (AJAX)
$router->post('/yeu-thich/toggle',      'WishlistController@toggle');

// ============================================================
// ADMIN ROUTES
// ============================================================

// Auth Admin
$router->get('/admin/dang-nhap',        'AdminAuthController@loginForm');
$router->post('/admin/dang-nhap',       'AdminAuthController@login');
$router->get('/admin/dang-xuat',        'AdminAuthController@logout');

// Dashboard
$router->get('/admin',                  'DashboardController@index');
$router->get('/admin/dashboard',        'DashboardController@index');

// Quản lý sản phẩm
$router->get('/admin/san-pham',             'AdminProductController@index');
$router->get('/admin/san-pham/them',        'AdminProductController@create');
$router->post('/admin/san-pham/them',       'AdminProductController@store');
$router->get('/admin/san-pham/sua/:id',     'AdminProductController@edit');
$router->post('/admin/san-pham/sua/:id',    'AdminProductController@update');
$router->post('/admin/san-pham/xoa/:id',    'AdminProductController@destroy');

// Quản lý đơn hàng
$router->get('/admin/don-hang',             'AdminOrderController@index');
$router->get('/admin/don-hang/:id',         'AdminOrderController@detail');
$router->post('/admin/don-hang/:id/trang-thai', 'AdminOrderController@updateStatus');

// Quản lý danh mục
$router->get('/admin/danh-muc',             'AdminCategoryController@index');
$router->post('/admin/danh-muc/them',       'AdminCategoryController@store');
$router->post('/admin/danh-muc/sua/:id',    'AdminCategoryController@update');
$router->post('/admin/danh-muc/xoa/:id',    'AdminCategoryController@destroy');

// Quản lý khách hàng
$router->get('/admin/khach-hang',           'AdminCustomerController@index');
$router->get('/admin/khach-hang/:id',       'AdminCustomerController@detail');

// Mã giảm giá
$router->get('/admin/ma-giam-gia',          'AdminCouponController@index');
$router->post('/admin/ma-giam-gia/them',    'AdminCouponController@store');
$router->post('/admin/ma-giam-gia/xoa/:id', 'AdminCouponController@destroy');

// Đánh giá
$router->get('/admin/danh-gia',             'AdminReviewController@index');
$router->post('/admin/danh-gia/:id/duyet',  'AdminReviewController@approve');
$router->post('/admin/danh-gia/:id/xoa',    'AdminReviewController@destroy');

// Báo cáo
$router->get('/admin/bao-cao',              'AdminReportController@index');
$router->get('/admin/bao-cao/xuat',         'AdminReportController@export');

// Cài đặt
$router->get('/admin/cai-dat',              'AdminSettingController@index');
$router->post('/admin/cai-dat',             'AdminSettingController@update');

// API nội bộ (AJAX)
$router->get('/admin/api/thong-ke',         'DashboardController@stats');
