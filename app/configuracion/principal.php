    <div class="card shadow-lg">
        <div class="text-center m-3">
             <h1 class="fw-bold text-center" style="color: #062974;">Bienvenido a la sección de configuración</h1>
            <hr class="mx-auto" style="width: 50px; height: 3px; background-color: #062974;">
            <p class="mt-3">Cómo habrás podido notar, esta sección es exclusiva únicamente para los administradores del
                sistema. Aquí podrás ver un registro de todos los movimientos que se han llevado a cabo en este mismo
                junto a realizar la gestión de usuarios. ¿Qué deseas hacer?</p>

            <div class="mt-4 d-flex flex-column align-items-center w-50 mx-auto">
                <div class="mb-3 w-100">
                    <a href="/IPSPUPTM/home.php?vista=usuarios"
                        class="btn btn-lg btn-primary d-flex flex-column align-items-center justify-content-center"
                        style="height: 120px;">
                        <i class="fas fa-users fa-3x mb-2"></i>
                        Gestión de usuarios
                    </a>
                </div>
                <div class="mb-3 w-100">
                    <a href="/IPSPUPTM/home.php?vista=bitacora"
                        class="btn btn-lg btn-primary d-flex flex-column align-items-center justify-content-center"
                        style="height: 120px;">
                        <i class="far fa-sticky-note fa-3x mb-2"></i>
                        Bitácora de movimientos
                    </a>
                </div>
                <div class="mb-3 w-100">
                    <form action="/IPSPUPTM/app/configuracion/respaldo.php" method="post" class="w-100">
                        <button type="submit"
                            class="btn btn-lg btn-primary d-flex flex-column align-items-center justify-content-center"
                            style="height: 120px; width: 100%;">
                            <i class="fas fa-save fa-3x mb-2"></i>
                            Generar Respaldo
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>