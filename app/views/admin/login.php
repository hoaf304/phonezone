<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Đăng nhập Admin – <?= APP_NAME ?></title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    *{margin:0;padding:0;box-sizing:border-box}
    body{font-family:'Inter',sans-serif;min-height:100vh;display:flex;background:#0f172a;overflow:hidden}
    .left-panel{flex:1;display:flex;flex-direction:column;justify-content:center;align-items:center;padding:3rem;position:relative;overflow:hidden;background:linear-gradient(135deg,#0f172a 0%,#1e293b 50%,#0f172a 100%)}
    .orb{position:absolute;border-radius:50%;filter:blur(60px);opacity:.35;animation:float 8s ease-in-out infinite}
    .orb1{width:400px;height:400px;background:#3b82f6;top:-100px;left:-100px;animation-delay:0s}
    .orb2{width:300px;height:300px;background:#8b5cf6;bottom:-80px;right:-80px;animation-delay:2s}
    .orb3{width:200px;height:200px;background:#06b6d4;top:40%;left:40%;animation-delay:4s}
    @keyframes float{0%,100%{transform:translateY(0) scale(1)}50%{transform:translateY(-20px) scale(1.05)}}
    .left-content{position:relative;z-index:1;text-align:center;max-width:420px}
    .left-logo{font-size:2.5rem;font-weight:800;color:#fff;letter-spacing:-1px;margin-bottom:1.5rem}
    .left-logo span{color:#3b82f6}
    .left-tagline{font-size:1rem;color:rgba(255,255,255,.6);line-height:1.8;margin-bottom:2.5rem}
    .preview-cards{display:grid;grid-template-columns:1fr 1fr;gap:.75rem}
    .preview-card{background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:12px;padding:1rem;text-align:left;backdrop-filter:blur(10px)}
    .pv-icon{font-size:1rem;margin-bottom:.5rem}
    .pv-icon.orange{color:#fb923c}.pv-icon.blue{color:#60a5fa}.pv-icon.green{color:#34d399}.pv-icon.purple{color:#a78bfa}
    .pv-label{font-size:.7rem;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.06em;margin-bottom:.25rem}
    .pv-value{font-size:1.2rem;font-weight:800;color:#fff}
    .right-panel{width:460px;flex-shrink:0;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:3rem 2.5rem}
    .form-logo{font-size:1.5rem;font-weight:800;color:#0f172a;margin-bottom:.25rem}
    .form-logo span{color:#3b82f6}
    .form-sub{color:#94a3b8;font-size:.88rem;margin-bottom:2rem}
    .alert-err{background:#fef2f2;border:1px solid #fecaca;color:#991b1b;padding:.75rem 1rem;border-radius:10px;font-size:.85rem;display:flex;align-items:center;gap:.5rem;margin-bottom:1.25rem}
    .form-group{margin-bottom:1.25rem}
    .form-label{display:block;font-size:.8rem;font-weight:600;color:#475569;margin-bottom:.4rem}
    .input-wrap{position:relative}
    .input-icon{position:absolute;left:.9rem;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:.85rem;pointer-events:none}
    .form-input{width:100%;padding:.75rem 1rem .75rem 2.5rem;border:2px solid #e2e8f0;border-radius:10px;font-size:.9rem;color:#1e293b;outline:none;transition:all .2s;font-family:inherit}
    .form-input:focus{border-color:#3b82f6;box-shadow:0 0 0 3px rgba(59,130,246,.12)}
    .input-toggle{position:absolute;right:.9rem;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;font-size:.9rem;padding:.2rem}
    .form-options{display:flex;align-items:center;margin-bottom:1.5rem}
    .remember-label{display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:#475569;cursor:pointer}
    .remember-label input{accent-color:#3b82f6;width:15px;height:15px}
    .btn-login{width:100%;padding:.85rem;background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;border:none;border-radius:10px;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .3s;display:flex;align-items:center;justify-content:center;gap:.5rem;font-family:inherit}
    .btn-login:hover{transform:translateY(-2px);box-shadow:0 8px 20px rgba(59,130,246,.4)}
    .btn-login:disabled{opacity:.7;cursor:not-allowed;transform:none}
    .spinner{display:none;width:16px;height:16px;border:2px solid rgba(255,255,255,.3);border-top-color:#fff;border-radius:50%;animation:spin .7s linear infinite;flex-shrink:0}
    @keyframes spin{to{transform:rotate(360deg)}}
    .divider{display:flex;align-items:center;gap:.75rem;margin:1.25rem 0;color:#cbd5e1;font-size:.75rem}
    .divider::before,.divider::after{content:'';flex:1;height:1px;background:#e2e8f0}
    .demo-hint{background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:.85rem 1rem;font-size:.82rem;color:#64748b;line-height:2}
    .demo-hint strong{color:#3b82f6}
    .copy-btn{background:#eff6ff;color:#1d4ed8;font-size:.7rem;font-weight:600;padding:.15rem .5rem;border-radius:5px;cursor:pointer;border:1px solid #bfdbfe;font-family:inherit;margin-left:.3rem}
    .copy-btn:hover{background:#dbeafe}
    .back-link{text-align:center;margin-top:1.5rem;font-size:.82rem;color:#94a3b8}
    .back-link a{color:#3b82f6;font-weight:600;text-decoration:none}
    @media(max-width:900px){.left-panel{display:none}.right-panel{width:100%;padding:2rem 1.5rem}}
  </style>
</head>
<body>
<div class="left-panel">
  <div class="orb orb1"></div><div class="orb orb2"></div><div class="orb orb3"></div>
  <div class="left-content">
    <div class="left-logo">Phone<span>Zone</span></div>
    <p class="left-tagline">Hệ thống quản trị toàn diện.<br>Kiểm soát mọi hoạt động kinh doanh<br>ngay trên một nền tảng duy nhất.</p>
    <div class="preview-cards">
      <div class="preview-card"><div class="pv-icon orange"><i class="fas fa-chart-line"></i></div><div class="pv-label">Doanh thu</div><div class="pv-value">Real-time</div></div>
      <div class="preview-card"><div class="pv-icon blue"><i class="fas fa-shopping-bag"></i></div><div class="pv-label">Đơn hàng</div><div class="pv-value">Tức thì</div></div>
      <div class="preview-card"><div class="pv-icon green"><i class="fas fa-users"></i></div><div class="pv-label">Khách hàng</div><div class="pv-value">Đầy đủ</div></div>
      <div class="preview-card"><div class="pv-icon purple"><i class="fas fa-box"></i></div><div class="pv-label">Kho hàng</div><div class="pv-value">Tự động</div></div>
    </div>
  </div>
</div>
<div class="right-panel">
  <div class="form-logo">Phone<span>Zone</span> <span style="font-size:1rem;font-weight:500;color:#94a3b8">Admin</span></div>
  <div class="form-sub">Đăng nhập để quản trị hệ thống</div>
  <?php if (Session::hasFlash('error')): ?>
  <div class="alert-err"><i class="fas fa-exclamation-circle"></i><?= e(Session::getFlash('error')) ?></div>
  <?php endif; ?>
  <form action="<?= url('admin/dang-nhap') ?>" method="POST" id="login-form">
    <?= Session::csrfField() ?>
    <div class="form-group">
      <label class="form-label">Địa chỉ Email</label>
      <div class="input-wrap">
        <i class="fas fa-envelope input-icon"></i>
        <input type="email" name="email" class="form-input" placeholder="admin@phonezone.vn" required autofocus value="<?= e($_POST['email'] ?? '') ?>">
      </div>
    </div>
    <div class="form-group">
      <label class="form-label">Mật khẩu</label>
      <div class="input-wrap">
        <i class="fas fa-lock input-icon"></i>
        <input type="password" name="mat_khau" id="pwd" class="form-input" placeholder="••••••••" required style="padding-right:2.75rem">
        <button type="button" class="input-toggle" onclick="togglePwd()"><i class="far fa-eye" id="eye-icon"></i></button>
      </div>
    </div>
    <div class="form-options">
      <label class="remember-label"><input type="checkbox" name="remember" value="1"> Ghi nhớ đăng nhập</label>
    </div>
    <button type="submit" class="btn-login" id="btn-submit">
      <div class="spinner" id="spinner"></div>
      <i class="fas fa-sign-in-alt" id="btn-icon"></i>
      <span id="btn-text">Đăng nhập</span>
    </button>
  </form>
  <div class="back-link"><a href="<?= url('') ?>"><i class="fas fa-arrow-left"></i> Về trang chủ</a></div>
</div>
<script>
function togglePwd(){const i=document.getElementById('pwd'),e=document.getElementById('eye-icon');i.type=i.type==='password'?'text':'password';e.className=i.type==='password'?'far fa-eye':'far fa-eye-slash'}
document.getElementById('login-form').addEventListener('submit',function(){document.getElementById('spinner').style.display='block';document.getElementById('btn-icon').style.display='none';document.getElementById('btn-text').textContent='Đang đăng nhập...';document.getElementById('btn-submit').disabled=true});
function copyText(t,b){navigator.clipboard.writeText(t).then(()=>{const o=b.textContent;b.textContent='✓ Copied';setTimeout(()=>b.textContent=o,1500)})}
</script>
</body>
</html>
