<?php
class HomeController extends Controller
{
    public function index(): void
    {
        $flashSale  = Product::getFlashSale(8);
        $featured   = Product::getFeatured(8);
        $bestSeller = Product::getBestSeller(4);
        $brands     = Brand::getAllVisible();

        $this->render('home.index', [
            'title'      => 'Trang chủ',
            'flashSale'  => $flashSale,
            'featured'   => $featured,
            'bestSeller' => $bestSeller,
            'brands'     => $brands,
        ]);
    }

    public function search(): void
    {
        $q = trim($this->input('q', ''));
        $this->redirect('san-pham' . ($q ? '?q=' . urlencode($q) : ''));
    }
}
