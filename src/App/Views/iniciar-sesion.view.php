    <aside id="login-panel" class="login-panel">
        <header class="login-header">
        <h2>Iniciar Sesión</h2>
        <button type="button" class="login-cerrar">
            <span class="material-symbols-outlined">close</span>
        </button>
        </header>

        <form class="login-form">
        <label for="user-login">Usuario</label>
        <input
            type="text"
            id="user-login"
            placeholder="Ingresá tu usuario"
            required
        />

        <label for="pass-login">Contraseña</label>
        <div class="campo-contraseña">
            <input
            type="password"
            id="pass-login"
            placeholder="Ingresá tu contraseña"
            required
            />
            <span class="material-symbols-outlined simbolos mostrar-contraseña">visibility_off</span>
        </div>

        <button type="submit">Iniciar Sesión</button>
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

        <form class="registro-form">
        <label for="name">Nombre Completo</label>
        <input type="text" id="name" placeholder="Ingresá tu nombre" required />

        <label for="mail">Correo Electrónico</label>
        <input
            type="email"
            id="mail"
            placeholder="Ingresá tu correo electrónico"
            required
        />

        <label for="user-register">Usuario</label>
        <input
            type="text"
            id="user-register"
            placeholder="Ingresá un usuario"
            required
        />

        <label for="pass-register">Contraseña</label>
        <div class="campo-contraseña">
            <input
            type="password"
            id="pass-register"
            placeholder="Ingresá tu contraseña"
            required
            />
            <span class="material-symbols-outlined simbolos mostrar-contraseña">visibility_off</span>
        </div>

        <button type="submit">Registrarme</button>
        </form>
</aside>