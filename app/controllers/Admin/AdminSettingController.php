<?php
class AdminSettingController extends AdminController
{
    public function index(): void
    {
        $settings = [];
        foreach (Database::fetchAll("SELECT * FROM cai_dat ORDER BY nhom, khoa") as $row) {
            $settings[$row['nhom']][$row['khoa']] = $row['gia_tri'];
        }
        $this->render('admin.settings.index', ['title' => 'Cài đặt hệ thống', 'settings' => $settings]);
    }

    public function update(): void
    {
        $this->verifyCsrf();
        $fields = $_POST;
        unset($fields['csrf_token']);

        foreach ($fields as $key => $value) {
            $exists = Database::fetchOne("SELECT khoa FROM cai_dat WHERE khoa = ?", [$key]);
            if ($exists) {
                Database::execute("UPDATE cai_dat SET gia_tri = ? WHERE khoa = ?", [trim($value), $key]);
            } else {
                Database::execute("INSERT INTO cai_dat (khoa, gia_tri, nhom) VALUES (?,?,?)", [$key, trim($value), 'chung']);
            }
        }
        Session::flash('success', 'Đã lưu cài đặt thành công!');
        $this->redirect('admin/cai-dat');
    }
}
