<input type="checkbox" id="mostrar-login" class="login-check" />
    <label for="mostrar-login" class="fondo-login"></label>
    <aside class="login-panel">
        <header class="login-header">
        <h2>Iniciar Sesión</h2>
        <label for="mostrar-login" class="login-cerrar">✕</label>
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

    <input type="checkbox" id="mostrar-registro" class="registro-check" />
    <label for="mostrar-registro" class="fondo-registro"></label>
    <aside class="registro-panel">
        <header class="registro-header">
        <h2>Registrarme</h2>
        <label for="mostrar-registro" class="registro-cerrar">✕</label>
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
            <span class="material-symbols-outlined simbolos mostrar-contraseña"
            >visibility_off</span
            >
        </div>

        <button type="submit">Registrarme</button>
        </form>
</aside>