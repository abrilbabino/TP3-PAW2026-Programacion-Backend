    <aside id="login-panel" class="login-panel">
        <header class="login-header">
        <h2>Iniciar Sesión</h2>
        <button type="button" class="login-cerrar">
            <span class="material-symbols-outlined">close</span>
        </button>
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
            <span class="material-symbols-outlined simbolos mostrar-contraseña">visibility_off</span>
        </div>

        <button type="submit" class="btn-primario">Iniciar Sesión</button>
        </form>
        <p>
        ¿No tenes cuenta aún?
        <span class="registro-link" id="btn-abrir-registro">Registrate aquí</span>
        </p>
    </aside>

    <aside id="registro-panel" class="registro-panel">
        <header class="registro-header">
        <h2>Registrarme</h2>
        <button type="button" class="registro-cerrar">
            <span class="material-symbols-outlined">close</span>
        </button>
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
            <span class="material-symbols-outlined simbolos mostrar-contraseña">visibility_off</span>
        </div>

        <button type="submit" class="btn-primario">Registrarme</button>
        </form>
</aside>