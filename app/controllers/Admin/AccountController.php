<?php

class AccountController extends Controller
{
    public function index(): void
    {
        echo "Trang tài khoản";
    }

    public function orders(): void
    {
        echo "Trang đơn hàng";
    }

    public function wishlist(): void
    {
        echo "Trang yêu thích";
    }

    public function update(): void
    {
        echo "Cập nhật tài khoản";
    }
}