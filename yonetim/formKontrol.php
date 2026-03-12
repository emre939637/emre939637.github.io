<style>
      /* Temel Form Stilleri */
    input[type="text"], input[type="password"] {
        border-radius: 10px;
        outline: none;
        border: none;
        background-color: #8acef34b;
        height: 35px;
        width: 200px;
        font-size: 1.1em;
        padding: 0 10px;
    }

    label { font-weight: bold; }

    .btn {
        height: 40px;
        margin-top: 20px;
        cursor: pointer;
        background-color: orange;
        color: white;
        font-weight: bold;
        border-radius: 50px;
        border: none;
        width: 200px;
        font-size: 20px;
    }

    /* Mevcut stillerin aynen kalabilir, ek olarak: */
    .hata-mesaji { color: red; font-size: 0.85em; font-weight: bold; margin-top: 2px; }
</style>

<form action="login.php" method="post">
    <div class="login">
        <p class="sfr">
            <label for="kad">Kullanıcı Adı:</label><br>
            <input type="text" name="kad" id="kad" value="<?php echo isset($_GET['kad']) ? htmlspecialchars($_GET['kad']) : ''; ?>" required>
            
            <?php if(isset($_GET['hata']) && $_GET['hata'] == 'kad'): ?>
                <span class="hata-mesaji">❌ Kullanıcı adı bulunamadı!</span>
            <?php endif; ?>
        </p>

        <p class="sfr">
            <label for="sifre">Şifre:</label><br>
            <input type="password" name="sifre" id="sifre" required>
            
            <?php if(isset($_GET['hata']) && $_GET['hata'] == 'sifre'): ?>
                <span class="hata-mesaji">❌ Şifre hatalı, lütfen tekrar deneyin!</span>
            <?php endif; ?>
        </p>
        
        <p><input type="submit" value="Giriş Yap" class="btn"></p>
        <p style="color:red; font-size: 0.8em;">*Üye Olmak İçin Yönetici İle İletişime Geçiniz.</p>
    </div>
</form>