<?php
class AdminProductController extends AdminController
{
    public function index(): void
    {
        $q       = trim($this->input('q', ''));
        $filter  = $this->input('filter', '');
        $page    = max(1, (int)$this->input('page', 1));
        $perPage = ADMIN_ITEMS_PER_PAGE;

        $where  = ['sp.an_hien >= 0'];
        $params = [];
        if ($q) { $where[] = '(sp.ten LIKE ? OR h.ten LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
        if ($filter === 'low_stock') { $where[] = 'bt_min.min_ton <= 5'; }

        $whereStr = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;

        $baseSql = "FROM san_pham sp
            JOIN hang_san_xuat h ON h.id = sp.hang_id
            JOIN danh_muc dm ON dm.id = sp.danh_muc_id
            LEFT JOIN (SELECT san_pham_id, MIN(ton_kho) AS min_ton, SUM(ton_kho) AS tong_ton,
                              MIN(COALESCE(gia_khuyen_mai,gia_ban)) AS gia_hien_thi
                       FROM bien_the_san_pham GROUP BY san_pham_id) bt_min ON bt_min.san_pham_id = sp.id
            WHERE $whereStr";

        $total    = (int)Database::fetchScalar("SELECT COUNT(*) $baseSql", $params);
        $stmt     = Database::pdo()->prepare("SELECT sp.*, h.ten AS hang_ten, dm.ten AS dm_ten,
                   bt_min.min_ton, bt_min.tong_ton, bt_min.gia_hien_thi
            $baseSql ORDER BY sp.created_at DESC LIMIT $perPage OFFSET $offset");
        foreach ($params as $i => $v) $stmt->bindValue($i+1, $v);
        $stmt->execute();
        $products = $stmt->fetchAll();

        $this->render('admin.products.index', [
            'title'    => 'Quản lý sản phẩm',
            'products' => $products,
            'pager'    => ['total'=>$total,'per_page'=>$perPage,'current_page'=>$page,'last_page'=>ceil($total/$perPage)],
            'q'        => $q, 'filter' => $filter,
        ]);
    }

    public function create(): void
    {
        $this->render('admin.products.form', [
            'title'      => 'Thêm sản phẩm',
            'product'    => null,
            'variants'   => [],
            'specs'      => [],
            'categories' => Category::getAllVisible(),
            'brands'     => Brand::getAllVisible(),
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        $data = $this->getProductData();

        $productId = Database::insert(
            "INSERT INTO san_pham (danh_muc_id,hang_id,ten,slug,mo_ta_ngan,mo_ta_chi_tiet,noi_bat,ban_chay,an_hien,meta_title,meta_desc,hinh_chinh)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
            [$data['danh_muc_id'],$data['hang_id'],$data['ten'],$data['slug'],
             $data['mo_ta_ngan'],$data['mo_ta_chi_tiet'],$data['noi_bat'],$data['ban_chay'],
             $data['an_hien'],$data['meta_title'],$data['meta_desc'],$data['hinh_chinh']]
        );

        $this->saveVariants($productId, $_POST['variants'] ?? []);
        $this->saveSpecs($productId, $_POST['spec_ten'] ?? [], $_POST['spec_gia_tri'] ?? []);

        Session::flash('success', 'Thêm sản phẩm thành công!');
        $this->redirect('admin/san-pham');
    }

    public function edit(string $id): void
    {
        $product = Product::find((int)$id);
        if (!$product) { $this->redirect('admin/san-pham'); return; }

        $this->render('admin.products.form', [
            'title'      => 'Sửa sản phẩm',
            'product'    => $product,
            'variants'   => Product::getVariants((int)$id),
            'specs'      => Product::getSpecs((int)$id),
            'categories' => Category::getAllVisible(),
            'brands'     => Brand::getAllVisible(),
        ]);
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $data = $this->getProductData();

        Database::execute(
            "UPDATE san_pham SET danh_muc_id=?,hang_id=?,ten=?,slug=?,mo_ta_ngan=?,mo_ta_chi_tiet=?,
             noi_bat=?,ban_chay=?,an_hien=?,meta_title=?,meta_desc=?"
            . (!empty($data['hinh_chinh']) ? ',hinh_chinh=?' : '')
            . " WHERE id=?",
            [...array_filter([
                $data['danh_muc_id'],$data['hang_id'],$data['ten'],$data['slug'],
                $data['mo_ta_ngan'],$data['mo_ta_chi_tiet'],$data['noi_bat'],$data['ban_chay'],
                $data['an_hien'],$data['meta_title'],$data['meta_desc'],
                !empty($data['hinh_chinh']) ? $data['hinh_chinh'] : null,
            ], fn($v) => $v !== null), (int)$id]
        );

        // Xóa và tạo lại biến thể + spec
        Database::execute("DELETE FROM bien_the_san_pham WHERE san_pham_id = ?", [$id]);
        Database::execute("DELETE FROM thong_so_ky_thuat WHERE san_pham_id = ?", [$id]);
        $this->saveVariants((int)$id, $_POST['variants'] ?? []);
        $this->saveSpecs((int)$id, $_POST['spec_ten'] ?? [], $_POST['spec_gia_tri'] ?? []);

        Session::flash('success', 'Cập nhật sản phẩm thành công!');
        $this->redirect('admin/san-pham');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        Database::execute("UPDATE san_pham SET an_hien = 0 WHERE id = ?", [$id]);
        Session::flash('success', 'Đã ẩn sản phẩm.');
        $this->redirect('admin/san-pham');
    }

    // ---- Helpers ----
    private function getProductData(): array
    {
        $ten      = trim($this->input('ten', ''));
        $slug     = $this->input('slug', '') ?: to_slug($ten);
        $hinh     = null;
        if (!empty($_FILES['hinh_chinh']['tmp_name'])) {
            $hinh = upload_image($_FILES['hinh_chinh'], 'products');
        }
        return [
            'danh_muc_id'   => (int)$this->input('danh_muc_id'),
            'hang_id'       => (int)$this->input('hang_id'),
            'ten'           => $ten,
            'slug'          => $slug,
            'mo_ta_ngan'    => $this->input('mo_ta_ngan', ''),
            'mo_ta_chi_tiet'=> $this->input('mo_ta_chi_tiet', ''),
            'noi_bat'       => $this->input('noi_bat', 0) ? 1 : 0,
            'ban_chay'      => $this->input('ban_chay', 0) ? 1 : 0,
            'an_hien'       => $this->input('an_hien', 1) ? 1 : 0,
            'meta_title'    => $this->input('meta_title', ''),
            'meta_desc'     => $this->input('meta_desc', ''),
            'hinh_chinh'    => $hinh,
        ];
    }

    private function saveVariants(int $productId, array $variants): void
    {
        foreach ($variants as $v) {
            $mauSac = trim($v['mau_sac'] ?? '');
            $giaBan = trim($v['gia_ban'] ?? '');

            // Bỏ qua nếu thiếu màu hoặc giá
            if ($mauSac === '' || $giaBan === '') continue;

            // Xóa tất cả ký tự không phải số (dấu chấm, phẩy, khoảng trắng)
            $giaBanInt = (int)preg_replace('/[^\d]/', '', $giaBan);
            $giaKM     = trim($v['gia_khuyen_mai'] ?? '');
            $giaKMInt  = $giaKM !== '' ? (int)preg_replace('/[^\d]/', '', $giaKM) : null;

            // SKU tự động nếu trống
            $sku = trim($v['sku'] ?? '');
            if ($sku === '') {
                $sku = strtoupper(substr(preg_replace('/[^A-Z0-9]/', '', $mauSac), 0, 3))
                     . '-' . date('ymd') . '-' . rand(100, 999);
            }

            Database::execute(
                "INSERT INTO bien_the_san_pham
                 (san_pham_id, mau_sac, dung_luong, sku, gia_ban, gia_khuyen_mai, ton_kho)
                 VALUES (?,?,?,?,?,?,?)",
                [
                    $productId,
                    $mauSac,
                    trim($v['dung_luong'] ?? ''),
                    $sku,
                    $giaBanInt,
                    $giaKMInt,
                    max(0, (int)($v['ton_kho'] ?? 0)),
                ]
            );
        }
    }

    private function saveSpecs(int $productId, array $tenArr, array $gtArr): void
    {
        foreach ($tenArr as $i => $ten) {
            if (empty(trim($ten))) continue;
            Database::execute(
                "INSERT INTO thong_so_ky_thuat (san_pham_id,ten_thong_so,gia_tri,thu_tu) VALUES (?,?,?,?)",
                [$productId, trim($ten), trim($gtArr[$i] ?? ''), $i]
            );
        }
    }
}
