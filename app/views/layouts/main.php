<?php
// app/views/layouts/main.php
// Biến $content đã được render từ Controller::render()
require VIEW_PATH . 'layouts/header.php';
echo $content;
require VIEW_PATH . 'layouts/footer.php';
