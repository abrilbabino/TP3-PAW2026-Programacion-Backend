<!doctype html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="/assets/img/icon.png">
    <link rel="stylesheet" href="/assets/css//style.css" />
    <link rel="stylesheet" href="/assets/css/print.css" media="print" />
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
    />
    <title>PawPrints</title>
  </head>
  <body>
    <header class="Barra-navegacion">
      <label for="menu-hamburguesa" class="label-hamburguesa">
        <img
          src="/asseys/img/menu-hamburguesa.png"
          alt="Abrir menú"
          class="icono-header"
        />
      </label>

      <figure class="header-logo">
        <img src="/assets/img/logo.png" alt="PAWPrints" />
      </figure>

      <label for="mostrar-carrito" class="icono-carrito">
        <img
          src="/assets/img/carrito-de-compras.png"
          alt="Abrir carrito"
          class="icono-header"
        />
      </label>

      <form action="/buscar" method="GET" class="header-busqueda">
        <label for="busqueda" class="busqueda-label"
          >Buscar en el sitio..</label
        >
        <input
          type="search"
          id="busqueda"
          name="busqueda"
          placeholder="Buscar en el sitio..."
          class="busqueda-input"
        />
        <button type="submit" class="busqueda-btn">Buscar</button>
      </form>

      <nav class="menu-principal">
        <input
          type="checkbox"
          id="menu-hamburguesa"
          class="menu-hamburguesa-check"
        />

        <ul class="nav-lista">
          <li>
            <a href="index.html" class="nav-link pagina-actual"
              ><span class="material-symbols-outlined simbolos">home</span
              >Inicio</a
            >
          </li>
          <li>
            <label for="mostrar-login" class="nav-link">
              <span class="material-symbols-outlined simbolos">login</span
              >Iniciar Sesión</label
            >
          </li>
          <li>
            <a href="SobreNosotros.html" class="nav-link"
              ><span class="material-symbols-outlined simbolos">groups</span
              >Sobre Nosotros</a
            >
          </li>
          <li>
            <a href="catalogo.html" class="nav-link"
              ><span class="material-symbols-outlined simbolos">menu_book</span>
              Catálogo</a
            >
          </li>
          <li>
            <a href="formulario.html" class="nav-link"
              ><span class="material-symbols-outlined simbolos">event</span
              >Reserva</a
            >
          </li>
          <li class="item-carrito-texto">
            <label for="mostrar-carrito" class="nav-link">
              <span class="material-symbols-outlined simbolos"
                >shopping_cart</span
              >Carrito
            </label>
          </li>
        </ul>
      </nav>
    </header>

    <main>
      <header class="Bienvenida">
        <h1>Bienvenido a PAWPrints</h1>
        <p>¡Descubrí un mundo de libros en nuestra tienda física y en línea!</p>
        <a href="catalogo.html">Ver Catálogo</a>
      </header>

      <section class="promociones">
        <h2>Promociones y Novedades</h2>
        <p class="ofertas-noticias">
          ¡Mirá nuestras últimas ofertas y noticias!
        </p>

        <article>
          <img src="/assets/img/nuevos-ingresos.png" alt="" />
          <h3>Nuevos Ingresos</h3>
          <p>Descubrí los últimos títulos agregados a nuestro catálogo.</p>
        </article>

        <article>
          <img src="/assets/img/descuento-mes.png" alt="" />
          <h3>Descuentos del Mes</h3>
          <p>
            Obtené hasta un 50% de descuento en artículos seleccionados este
            mes.
          </p>
        </article>

        <article>
          <img src="/assets/img/eventos.png" alt="" />
          <h3>Eventos</h3>
          <p>¡Sumate a nuestros próximos eventos de firma de libros!</p>
        </article>
      </section>

      <section class="tienda-fisica">
        <h2>Tienda Física</h2>
        <figure>
          <iframe
            src="https://www.google.com/maps/d/embed?mid=1fqEzq6nPa6IITzVkvMWnMvfQsy2sqrQ&ehbc=2E312F&noprof=1"
            width="640"
            height="480"
          ></iframe>
        </figure>

        <address>
          <p><strong>Horarios de Atención:</strong></p>
          <ul>
            <li>Lun-Vie: 9 AM - 9 PM</li>
            <li>Sáb-Dom: 10 AM - 6 PM</li>
          </ul>
        </address>
      </section>
    </main>

    <footer class="footer-principal">
      <address class="footer-direccion">
        <p class="footer-email">
          Email: <a href="mailto:info@pawprints.com">info@pawprints.com</a>
        </p>
        <p class="footer-redes">Seguinos en nuestras redes:</p>
        <ul class="footer-redes-lista">
          <li>
            <a href="https://facebook.com" class="icono-red">
              <span class="texto-red">Facebook</span
              ><img src="/assets/img/facebook.png" alt="Facebook" class="img-red"
            /></a>
          </li>
          <li>
            <a href="https://twitter.com" class="icono-red"
              ><span class="texto-red">Twitter</span
              ><img src="/assets/img/twitter.png" alt="Twitter" class="img-red"
            /></a>
          </li>
          <li>
            <a href="https://instagram.com" class="icono-red"
              ><span class="texto-red">Instagram</span
              ><img src="/assets/img/instagram.png" alt="Instagram" class="img-red"
            /></a>
          </li>
        </ul>
      </address>

      <p class="footer-copyright"><small>&copy; 2026 PAWPrints</small></p>
    </footer>

    <input type="checkbox" id="mostrar-login" class="login-check" />
    <label for="mostrar-login" class="fondo-login"></label>
    <aside class="login-panel">
      <header class="login-header">
        <h2>Iniciar Sesión</h2>
        <label for="mostrar-login" class="login-cerrar">✕</label>
      </header>

      <form class="login-form">
        <label for="user">Usuario</label>
        <input
          type="text"
          id="user"
          placeholder="Ingresá tu usuario"
          required
        />

        <label for="pass">Contraseña</label>
        <div class="campo-contraseña">
          <input
            type="password"
            id="pass"
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

        <label for="user">Usuario</label>
        <input
          type="text"
          id="user"
          placeholder="Ingresá un usuario"
          required
        />

        <label for="pass">Contraseña</label>
        <div class="campo-contraseña">
          <input
            type="password"
            id="pass"
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

    <input type="checkbox" id="mostrar-carrito" class="carrito-check" />
    <label for="mostrar-carrito" class="fondo-carrito"></label>
    <aside class="carrito-panel">
      <header class="carrito-header">
        <h2>Carrito de compras</h2>
        <label for="mostrar-carrito" class="carrito-cerrar">✕</label>
      </header>

      <p class="carrito-vacio">
        <span class="material-symbols-outlined simbolos">info</span>
        El carrito de compras está vacío.
      </p>
    </aside>
  </body>
</html>
