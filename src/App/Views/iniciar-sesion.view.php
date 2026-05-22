<input type="checkbox" id="mostrar-login" class="login-check" <?= isset($_SESSION['error_login']) ? 'checked' : '' ?> />
    <label for="mostrar-login" class="fondo-login"></label>
    <aside class="login-panel">
        <header class="login-header">
        <h2>Iniciar Sesión</h2>
        <label for="mostrar-login" class="login-cerrar">✕</label>
        </header>

        <form class="login-form" action="/login" method="POST">
        <?php if (isset($_SESSION['error_login'])): ?>
            <p class="error-auth"><?= $_SESSION['error_login']; unset($_SESSION['error_login']); ?></p>
        <?php endif; ?>
        <label for="user-login">Usuario</label>
        <input
            type="text"
            id="user-login"
            name="nombre_usuario"
            placeholder="Ingresá tu usuario"
            required
        />

        <label for="pass-login">Contraseña</label>
        <div class="campo-contraseña">
            <input
            type="password"
            id="pass-login"
            name="contrasena"
            placeholder="Ingresá tu contraseña"
            required
            />
            <span class="material-symbols-outlined simbolos mostrar-contraseña"
            >visibility_off</span
            >
        </div>

        <button type="submit">Iniciar Sesión</button>
        </form>
        <p>
        ¿No tenes cuenta aún?
        <label for="mostrar-registro" class="registro-link"
            >Registrate aquí</label
        >
        </p>
    </aside>

    <input type="checkbox" id="mostrar-registro" class="registro-check" <?= isset($_SESSION['error_registro']) ? 'checked' : '' ?> />
    <label for="mostrar-registro" class="fondo-registro"></label>
    <aside class="registro-panel">
        <header class="registro-header">
        <h2>Registrarme</h2>
        <label for="mostrar-registro" class="registro-cerrar">✕</label>
        </header>

        <form class="registro-form" action="/register" method="POST">
        <?php if (isset($_SESSION['error_registro'])): ?>
            <p class="error-auth"><?= $_SESSION['error_registro']; unset($_SESSION['error_registro']); ?></p>
        <?php endif; ?>
        <label for="name">Nombre Completo</label>
        <input type="text" id="name" name="name" placeholder="Ingresá tu nombre" required />

        <label for="mail">Correo Electrónico</label>
        <input
            type="email"
            id="mail"
            name="email"
            placeholder="Ingresá tu correo electrónico"
            required
        />

        <label for="user-register">Usuario</label>
        <input
            type="text"
            id="user-register"
            name="username"
            placeholder="Ingresá un usuario"
            required
        />

        <label for="pass-register">Contraseña</label>
        <div class="campo-contraseña">
            <input
            type="password"
            id="pass-register"
            name="password"
            placeholder="Ingresá tu contraseña"
            required
            />
            <span class="material-symbols-outlined simbolos mostrar-contraseña"
            >visibility_off</span
            >
        </div>

        <button type="submit">Registrarme</button>
        </form>
</aside>