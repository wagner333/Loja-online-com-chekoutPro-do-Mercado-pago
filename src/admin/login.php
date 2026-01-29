<h1>Login Admin</h1>
<?php if (isset($login_error)): ?>
<p style="color: red;"><?php echo $login_error; ?></p>
<?php endif; ?>
<form method="POST">
    <input type="hidden" name="admin_login" value="1">
    <div>
        <label>Usuário:</label>
        <input type="text" name="username" required>
    </div>
    <div>
        <label>Senha:</label>
        <input type="password" name="password" required>
    </div>
    <button type="submit">Entrar</button>
</form>