<?php
class WishlistController extends Controller
{
    public function toggle(): void
    {
        if (!Session::has('user_id')) {
            $this->json(['success' => false, 'redirect' => true]); return;
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $spId  = (int)($input['san_pham_id'] ?? 0);
        $uid   = (int)Session::get('user_id');

        if (!$spId) { $this->json(['success' => false]); return; }

        $existing = Database::fetchOne(
            "SELECT id FROM yeu_thich WHERE khach_hang_id = ? AND san_pham_id = ?", [$uid, $spId]
        );

        if ($existing) {
            Database::execute("DELETE FROM yeu_thich WHERE id = ?", [$existing['id']]);
            $this->json(['success' => true, 'liked' => false]);
        } else {
            Database::execute(
                "INSERT INTO yeu_thich (khach_hang_id, san_pham_id) VALUES (?,?)", [$uid, $spId]
            );
            $this->json(['success' => true, 'liked' => true]);
        }
    }
}
