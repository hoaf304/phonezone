<?php
class AdminProductController extends AdminController
{
    public function index(): void
    {
        $q       = trim($this->input('q', ''));
        $filter  = $this->input('filter', '');
        $page    = max(1, (int)$this->input('page', 1));
        $perPage = ADMIN_ITEMS_PER_PAGE;
        $where   = ['sp.an_hien >= 0']; $params = [];
        if ($q) { $where[] = '(sp.ten LIKE ? OR h.ten LIKE ?)'; $params[] = "%$q%"; $params[] = "%$q%"; }
        if ($filter === 'low_stock') { $where[] = 'bt_min.min_ton <= 5'; }
        $whereStr = implode(' AND ', $where);
        $offset   = ($page - 1) * $perPage;
        $baseSql  = "FROM san_pham sp
            JOIN hang_san_xuat h ON h.id=sp.hang_id JOIN danh_muc dm ON dm.id=sp.danh_muc_id
            LEFT JOIN (SELECT san_pham_id,MIN(ton_kho) AS min_ton,SUM(ton_kho) AS tong_ton,
                       MIN(COALESCE(gia_khuyen_mai,gia_ban)) AS gia_hien_thi
                       FROM bien_the_san_pham GROUP BY san_pham_id) bt_min ON bt_min.san_pham_id=sp.id
            WHERE $whereStr";
        $total = (int)Database::fetchScalar("SELECT COUNT(*) $baseSql", $params);
        $stmt  = Database::pdo()->prepare("SELECT sp.*,h.ten AS hang_ten,dm.ten AS dm_ten,
                 bt_min.min_ton,bt_min.tong_ton,bt_min.gia_hien_thi
                 $baseSql ORDER BY sp.created_at DESC LIMIT $perPage OFFSET $offset");
        foreach ($params as $i => $v) $stmt->bindValue($i+1,$v);
        $stmt->execute();
        $this->render('admin.products.index', [
            'title'    => 'Quản lý sản phẩm',
            'products' => $stmt->fetchAll(),
            'pager'    => ['total'=>$total,'per_page'=>$perPage,'current_page'=>$page,'last_page'=>(int)ceil($total/$perPage)],
            'q' => $q, 'filter' => $filter,
        ]);
    }

    public function create(): void
    {
        $this->render('admin.products.form', [
            'title'      => 'Thêm sản phẩm',
            'product'    => null, 'variants' => [], 'specs' => [], 'images' => [],
            'categories' => Category::getAllVisible(),
            'brands'     => Brand::getAllVisible(),
        ]);
    }

    public function store(): void
    {
        $this->verifyCsrf();
        if (isset($_POST['__debug'])) {
            echo "<pre style='background:#1e293b;color:#e2e8f0;padding:20px'>";
            echo "variants:\n"; print_r($_POST['variants'] ?? 'EMPTY');
            echo "\nPOST keys: " . implode(', ', array_keys($_POST));
            echo "</pre>"; exit;
        }
        $data      = $this->getProductData();
        $productId = Database::insert(
            "INSERT INTO san_pham (danh_muc_id,hang_id,ten,slug,mo_ta_ngan,mo_ta_chi_tiet,noi_bat,ban_chay,an_hien,meta_title,meta_desc,hinh_chinh) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)",
            [$data['danh_muc_id'],$data['hang_id'],$data['ten'],$data['slug'],
             $data['mo_ta_ngan'],$data['mo_ta_chi_tiet'],$data['noi_bat'],$data['ban_chay'],
             $data['an_hien'],$data['meta_title'],$data['meta_desc'],$data['hinh_chinh']]
        );
        $this->saveVariants($productId, $_POST['variants'] ?? []);
        $this->saveSpecs($productId, $_POST['spec_ten'] ?? [], $_POST['spec_gia_tri'] ?? []);
        $this->saveGalleryImages($productId);
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
            'images'     => Product::getImages((int)$id),
            'categories' => Category::getAllVisible(),
            'brands'     => Brand::getAllVisible(),
        ]);
    }

    public function update(string $id): void
    {
        $this->verifyCsrf();
        $data   = $this->getProductData();
        $sets   = "danh_muc_id=?,hang_id=?,ten=?,slug=?,mo_ta_ngan=?,mo_ta_chi_tiet=?,noi_bat=?,ban_chay=?,an_hien=?,meta_title=?,meta_desc=?";
        $params = [$data['danh_muc_id'],$data['hang_id'],$data['ten'],$data['slug'],
                   $data['mo_ta_ngan'],$data['mo_ta_chi_tiet'],$data['noi_bat'],$data['ban_chay'],
                   $data['an_hien'],$data['meta_title'],$data['meta_desc']];
        if (!empty($data['hinh_chinh'])) { $sets .= ',hinh_chinh=?'; $params[] = $data['hinh_chinh']; }
        $params[] = (int)$id;
        Database::execute("UPDATE san_pham SET $sets WHERE id=?", $params);
        Database::execute("DELETE FROM bien_the_san_pham WHERE san_pham_id=?", [$id]);
        Database::execute("DELETE FROM thong_so_ky_thuat WHERE san_pham_id=?", [$id]);
        $this->saveVariants((int)$id, $_POST['variants'] ?? []);
        $this->saveSpecs((int)$id, $_POST['spec_ten'] ?? [], $_POST['spec_gia_tri'] ?? []);
        $this->deleteMarkedImages();
        $this->saveGalleryImages((int)$id);
        Session::flash('success', 'Cập nhật sản phẩm thành công!');
        $this->redirect('admin/san-pham');
    }

    public function destroy(string $id): void
    {
        $this->verifyCsrf();
        Database::execute("UPDATE san_pham SET an_hien=0 WHERE id=?", [$id]);
        Session::flash('success', 'Đã ẩn sản phẩm.');
        $this->redirect('admin/san-pham');
    }

    // ============================================================
    private function getProductData(): array
    {
        $ten  = trim($this->input('ten', ''));
        $slug = trim($this->input('slug', '')) ?: to_slug($ten);
        $hinh = null;
        if (!empty($_FILES['hinh_chinh']['tmp_name'])) {
            $hinh = upload_image($_FILES['hinh_chinh'], 'products');
        } elseif (!empty($_POST['hinh_chinh_url'])) {
            $hinh = trim($_POST['hinh_chinh_url']);
        }
        return [
            'danh_muc_id'    => (int)$this->input('danh_muc_id'),
            'hang_id'        => (int)$this->input('hang_id'),
            'ten'            => $ten, 'slug' => $slug,
            'mo_ta_ngan'     => $this->input('mo_ta_ngan', ''),
            'mo_ta_chi_tiet' => $this->input('mo_ta_chi_tiet', ''),
            'noi_bat'        => $this->input('noi_bat',  0) ? 1 : 0,
            'ban_chay'       => $this->input('ban_chay', 0) ? 1 : 0,
            'an_hien'        => $this->input('an_hien',  1) ? 1 : 0,
            'meta_title'     => $this->input('meta_title', ''),
            'meta_desc'      => $this->input('meta_desc',  ''),
            'hinh_chinh'     => $hinh,
        ];
    }

    private function saveGalleryImages(int $productId): void
    {
        $order = (int)Database::fetchScalar(
            "SELECT COALESCE(MAX(thu_tu),0) FROM hinh_anh_san_pham WHERE san_pham_id=?", [$productId]
        );
        // Upload nhiều file
        if (!empty($_FILES['hinh_thu_vien']['name'][0])) {
            $files = $_FILES['hinh_thu_vien'];
            for ($i = 0; $i < min(count($files['tmp_name']), 10); $i++) {
                if (empty($files['tmp_name'][$i])) continue;
                $path = upload_image([
                    'name'=>$files['name'][$i],'type'=>$files['type'][$i],
                    'tmp_name'=>$files['tmp_name'][$i],'error'=>$files['error'][$i],'size'=>$files['size'][$i]
                ], 'products');
                if ($path) Database::execute(
                    "INSERT INTO hinh_anh_san_pham (san_pham_id,url,thu_tu) VALUES (?,?,?)",
                    [$productId,$path,++$order]
                );
            }
        }
        // URL thư viện
        foreach ($_POST['gallery_urls'] ?? [] as $url) {
            $url = trim($url);
            if (!$url || !str_starts_with($url,'http')) continue;
            Database::execute("INSERT INTO hinh_anh_san_pham (san_pham_id,url,thu_tu) VALUES (?,?,?)", [$productId,$url,++$order]);
        }
    }

    private function deleteMarkedImages(): void
    {
        foreach (array_filter($_POST['delete_images'] ?? []) as $imgId) {
            $img = Database::fetchOne("SELECT url FROM hinh_anh_san_pham WHERE id=?", [(int)$imgId]);
            if ($img && !str_starts_with($img['url'],'http')) {
                $f = UPLOAD_PATH . $img['url'];
                if (file_exists($f)) @unlink($f);
            }
            Database::execute("DELETE FROM hinh_anh_san_pham WHERE id=?", [(int)$imgId]);
        }
    }

    private function saveVariants(int $productId, array $variants): void
    {
        foreach ($variants as $v) {
            if (!is_array($v)) continue;
            $mauSac = trim($v['mau_sac'] ?? '');
            $giaBan = trim($v['gia_ban'] ?? '');
            if ($mauSac === '' || $giaBan === '') continue;
            $giaBanInt = (int)preg_replace('/[^\d]/','',$giaBan);
            if ($giaBanInt <= 0) continue;
            $giaKMRaw = trim($v['gia_khuyen_mai'] ?? '');
            $giaKMInt = $giaKMRaw !== '' ? (int)preg_replace('/[^\d]/','',$giaKMRaw) : null;
            if ($giaKMInt === 0) $giaKMInt = null;
            $sku = trim($v['sku'] ?? '');
            if (!$sku) { $p=strtoupper(preg_replace('/[^A-Z0-9]/i','',$mauSac)); $sku=(substr($p,0,4)?:'SP').'-'.$productId.'-'.rand(100,999); }
            Database::execute(
                "INSERT INTO bien_the_san_pham (san_pham_id,mau_sac,dung_luong,sku,gia_ban,gia_khuyen_mai,ton_kho) VALUES (?,?,?,?,?,?,?)",
                [$productId,$mauSac,trim($v['dung_luong']??''),$sku,$giaBanInt,$giaKMInt,max(0,(int)($v['ton_kho']??0))]
            );
        }
    }

    private function saveSpecs(int $productId, array $tenArr, array $gtArr): void
    {
        foreach ($tenArr as $i => $ten) {
            $ten = trim($ten);
            if (!$ten) continue;
            Database::execute("INSERT INTO thong_so_ky_thuat (san_pham_id,ten_thong_so,gia_tri,thu_tu) VALUES (?,?,?,?)",
                [$productId,$ten,trim($gtArr[$i]??''),$i]);
        }
    }
}
