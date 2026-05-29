<?php
class ProductController extends Controller
{
    // ---- Danh sách sản phẩm ----
    public function list(): void
    {
        $filters = [
            'q'        => trim($this->input('q', '')),
            'hang'     => $this->input('hang', ''),
            'danh_muc' => $this->input('danh_muc', ''),
            'gia_tu'   => $this->input('gia_tu', ''),
            'gia_den'  => $this->input('gia_den', ''),
            'sao'      => $this->input('sao', ''),
            'sort'     => $this->input('sort', 'moi_nhat'),
        ];
        $page   = max(1, (int)$this->input('page', 1));
        $result = Product::getList($filters, $page);
        $brands = Brand::getAllVisible();
        $categories = Category::getAllVisible();

        // Tiêu đề động
        $title = 'Tất cả sản phẩm';
        if ($filters['q'])    $title = 'Tìm kiếm: ' . $filters['q'];
        if ($filters['hang']) $title = 'Hãng: ' . ucfirst($filters['hang']);

        $this->render('product.list', [
            'title'      => $title,
            'extraCss'   => ['products.css'],
            'products'   => $result['items'],
            'pager'      => $result,
            'filters'    => $filters,
            'brands'     => $brands,
            'categories' => $categories,
        ]);
    }

    // ---- Danh sách theo danh mục ----
    public function byCategory(string $slug): void
    {
        $category = Category::getBySlug($slug);
        if (!$category) { $this->redirect('san-pham'); return; }

        $filters = ['danh_muc' => $slug, 'sort' => $this->input('sort', 'moi_nhat')];
        $page    = max(1, (int)$this->input('page', 1));
        $result  = Product::getList($filters, $page);
        $brands  = Brand::getAllVisible();

        $this->render('product.list', [
            'title'      => $category['ten'],
            'extraCss'   => ['products.css'],
            'products'   => $result['items'],
            'pager'      => $result,
            'filters'    => $filters,
            'brands'     => $brands,
            'categories' => Category::getAllVisible(),
            'currentCategory' => $category,
        ]);
    }

    // ---- Danh sách theo hãng ----
    public function byBrand(string $slug): void
    {
        $brand = Brand::getBySlug($slug);
        if (!$brand) { $this->redirect('san-pham'); return; }

        $filters = ['hang' => $slug, 'sort' => $this->input('sort', 'moi_nhat')];
        $page    = max(1, (int)$this->input('page', 1));
        $result  = Product::getList($filters, $page);

        $this->render('product.list', [
            'title'        => $brand['ten'],
            'extraCss'     => ['products.css'],
            'products'     => $result['items'],
            'pager'        => $result,
            'filters'      => $filters,
            'brands'       => Brand::getAllVisible(),
            'categories'   => Category::getAllVisible(),
            'currentBrand' => $brand,
        ]);
    }

    // ---- Chi tiết sản phẩm ----
    public function detail(string $slug): void
    {
        $product = Product::getBySlug($slug);
        if (!$product) { $this->redirect('san-pham'); return; }

        // Tăng lượt xem
        Product::incrementView($product['id']);

        $variants = Product::getVariants($product['id']);
        $images   = Product::getImages($product['id']);
        $specs    = Product::getSpecs($product['id']);
        $reviews  = Review::getByProduct($product['id'], 5);
        $related  = Product::getRelated($product['danh_muc_id'], $product['id'], 4);

        // Thống kê đánh giá
        $reviewStats = Review::getStats($product['id']);

        // Biến thể mặc định (rẻ nhất)
        $defaultVariant = !empty($variants) ? $variants[0] : null;

        $this->render('product.detail', [
            'title'          => $product['ten'],
            'metaDesc'       => $product['meta_desc'] ?? $product['mo_ta_ngan'],
            'extraCss'       => ['products.css'],
            'product'        => $product,
            'variants'       => $variants,
            'images'         => $images,
            'specs'          => $specs,
            'reviews'        => $reviews,
            'reviewStats'    => $reviewStats,
            'related'        => $related,
            'defaultVariant' => $defaultVariant,
        ]);
    }

    // ---- So sánh sản phẩm ----
    public function compare(): void
    {
        $ids      = array_map('intval', Session::get('compare', []));
        $products = [];
        foreach ($ids as $id) {
            // Dùng query có JOIN để lấy đủ hang_ten, danh_muc_ten
            $p = Database::fetchOne(
                "SELECT sp.*, h.ten AS hang_ten, h.slug AS hang_slug,
                        dm.ten AS danh_muc_ten, dm.slug AS danh_muc_slug
                 FROM san_pham sp
                 JOIN hang_san_xuat h ON h.id = sp.hang_id
                 JOIN danh_muc dm     ON dm.id = sp.danh_muc_id
                 WHERE sp.id = ? AND sp.an_hien = 1",
                [$id]
            );
            if ($p) {
                $p['variants'] = Product::getVariants($p['id']);
                $p['specs']    = Product::getSpecs($p['id']);
                $products[]    = $p;
            }
        }
        $this->render('product.compare', [
            'title'    => 'So sánh sản phẩm',
            'extraCss' => ['products.css', 'account.css'],
            'products' => $products,
        ]);
    }

    // ---- AJAX: Thêm vào so sánh ----
    public function addCompare(string $id = ''): void
    {
        // Đọc từ JSON body (AJAX) hoặc POST form
        $input = [];
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $input = json_decode(file_get_contents('php://input'), true) ?? [];
        }
        $spId = (int)($id ?: $input['san_pham_id'] ?? $_POST['san_pham_id'] ?? 0);

        if (!$spId) {
            $this->json(['success' => false, 'message' => 'ID sản phẩm không hợp lệ.']);
            return;
        }

        // Lấy session, đảm bảo toàn bộ phần tử là int
        $list = array_map('intval', Session::get('compare', []));

        if (in_array($spId, $list, true)) {
            $this->json(['success' => false, 'message' => 'Sản phẩm đã có trong danh sách so sánh.']);
        } elseif (count($list) >= 3) {
            $this->json(['success' => false, 'message' => 'Chỉ so sánh tối đa 3 sản phẩm.']);
        } else {
            $list[] = $spId;
            Session::set('compare', $list);
            $this->json(['success' => true, 'count' => count($list)]);
        }
    }

    // ---- Xóa khỏi so sánh ----
    public function removeCompare(string $id): void
    {
        $spId = (int)$id;
        $list = array_map('intval', Session::get('compare', []));
        $list = array_values(array_filter($list, fn($x) => $x !== $spId));
        Session::set('compare', $list);

        if ($this->isAjax()) {
            $this->json(['success' => true, 'count' => count($list)]);
        } else {
            $this->redirect('so-sanh');
        }
    }

    private function isAjax(): bool
    {
        return ($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHttpRequest';
    }

    // ---- Xóa toàn bộ so sánh ----
    public function clearCompare(): void
    {
        Session::remove('compare');
        if ($this->isAjax()) {
            $this->json(['success' => true, 'count' => 0]);
        } else {
            $this->redirect('so-sanh');
        }
    }

}