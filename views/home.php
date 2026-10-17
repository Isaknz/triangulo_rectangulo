<?php
session_start();
require_once __DIR__ . '/../controller/AuthController.php';
// 1. Cambiamos al controlador correspondiente (debes crearlo o renombrarlo)
require_once __DIR__ . '/../controller/TrianguloController.php';

AuthController::verificarSesion();

$trianguloController = new TrianguloController();
$mensaje = $_GET['mensaje'] ?? '';
$mensajeFormulario = $trianguloController->procesarFormulario();

if ($mensajeFormulario !== '') {
    $mensaje = $mensajeFormulario;
}

$buscar = trim($_GET['buscar'] ?? '');
$registroEditar = null;

if (isset($_GET['editar'])) {
    $registroEditar = $trianguloController->obtenerParaEditar((int) $_GET['editar']);
}

$registros = $trianguloController->listar($buscar);

$idActual = $registroEditar['id_triangulo'] ?? '';
$catetoAActual = $registroEditar['cateto_a'] ?? '';
$catetoBActual = $registroEditar['cateto_b'] ?? '';
$hipotenusaActual = $registroEditar['hipotenusa'] ?? '';
$detalleActual = $registroEditar['detalle'] ?? '';
$areaActual = $registroEditar['area'] ?? '';
$perimetroActual = $registroEditar['perimetro'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index,follow">
    <!-- Ajuste de metadatos -->
    <meta name="description" content="Cálculo del área y perímetro del triángulo rectángulo">
    <meta name="keywords" content="triangulo, rectangulo, pitagoras, sistema, base de datos">
    <meta name="author" content="BC TECH">

    <link rel="icon" href="../imagenes/icono_isaknz.png">
    <link rel="stylesheet" href="../assetes/css/style_home.css">

    <title>Triángulo Rectángulo - Principal</title>
</head>
<body>
    <header class="headeerHome">
        <div class="lineaSup">
            <div class="logo-links">
                <div class="logo">
                    <img src="../imagenes/logo_home2.png" alt="">
                    <h1>Matemáticas</h1>
                </div>               
                <a href="">Quiénes somos</a>
            </div>
            <div class="buscarHeader">
                <form class="form1" action="home.php" method="get">
                    <img src="../imagenes/logo.png" alt="imagen de buscar">
                    <button type="submit">Buscar</button>
                    <input type="text" name="buscar" id="txtBuscar" placeholder="BUSCAR"
                    value="<?php echo htmlspecialchars($buscar); ?>">
                </form>
            </div>
        </div>
        <div class="lineaInf">
            <nav class="menu">
                <li><a href="#">Inicio</a></li>
                <li><a href="">Nuestra historia</a></li>
                <!-- Corregido error de escritura "cinfirm" por "confirm" -->
                <li><a href="logout.php" onclick="return confirm('¿Desea salir?');">Cerrar sesión</a></li>
            </nav>
        </div>
    </header>

    <main>
        <div class="datos">
            <div class="titulo">
                <h1>Cálculo del Área y Perímetro de un Triángulo Rectángulo</h1>
                <p>Usuario: <?php echo htmlspecialchars($_SESSION['usuario']); ?></p>
            </div>

            <?php if ($mensaje !== '') : ?>
                <p class="mensaje">
                    <?php echo htmlspecialchars(ucfirst($mensaje)); ?>
                </p>
            <?php endif; ?>

            <form action="" method="post" class="form2" id="frmFormulario">
                <!-- ID adaptado -->
                <input type="hidden" name="id_triangulo" value="<?php echo htmlspecialchars($idActual); ?>">

                <!-- Entrada de datos: Cateto A y Cateto B -->
                <div class="inputs">
                    <label for="txtCatetoA">Cateto A (Base)</label>
                    <input class="inputHome" type="number" step="any" name="txtCatetoA" id="txtCatetoA" placeholder="Ej: 3"
                     value="<?php echo htmlspecialchars($catetoAActual); ?>" required>
                </div>
                <div class="inputs">
                    <label for="txtCatetoB">Cateto B (Altura)</label>
                    <input class="inputHome" type="number" step="any" name="txtCatetoB" id="txtCatetoB" placeholder="Ej: 4"
                     value="<?php echo htmlspecialchars($catetoBActual); ?>" required>
                </div>
                <div class="inputs">
                    <label for="txtHipotenusa">Hipotenusa</label>
                    <input class="inputHome" type="text" name="txtHipotenusa" id="txtHipotenusa" readonly placeholder="Calculado"
                     value="<?php echo htmlspecialchars($hipotenusaActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtDetalle">Comentario</label>
                    <input class="inputHome" type="text" placeholder="Opcional: Un comentario"
                    name="txtDetalle" id="txtDetalle" value="<?php echo htmlspecialchars($detalleActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtA">Área</label>
                    <input class="inputHome" type="text" name="txtA" id="txtA" readonly placeholder="Área"
                     value="<?php echo htmlspecialchars($areaActual); ?>">
                </div>
                <div class="inputs">
                    <label for="txtP">Perímetro</label>
                    <input class="inputHome" type="text" name="txtP" id="txtP" readonly placeholder="Perímetro"
                     value="<?php echo htmlspecialchars($perimetroActual); ?>">
                </div>
                <div class="botones">
                    <!-- Función JS actualizada -->
                    <button type="button" onclick="calcularTriangulo(true);">Calcular</button>
                    <a class="btn-link cancelar" href="home.php">Cancelar</a>
                    <button type="submit" name="accion" value="guardar">
                        <?php echo $idActual !== '' ? 'Actualizar' : 'Guardar'; ?>
                    </button>
                </div>
            </form>
        </div>

        <div class="buscar">
            <form action="home.php" method="get" id="frmBuscar">
                <img src="../imagenes/logo.png" alt="imagen de buscar">
                <button type="submit">Buscar</button>
                <input type="text" name="buscar" id="txtBuscar2" placeholder="Buscar por comentario"
                 value="<?php echo htmlspecialchars($buscar); ?>">
                <a class="btn-link limpiar" href="home.php">Limpiar</a>
            </form>
        </div>

        <div class="tabla">
            <h1>Historial de los cálculos</h1>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cateto A</th>
                        <th>Cateto B</th>
                        <th>Hipotenusa</th>
                        <th>Detalle</th>
                        <th>Área</th>
                        <th>Perímetro</th>
                        <th>Fecha y hora</th>
                        <th>Botones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($registros) > 0) : ?>
                        <?php foreach ($registros as $fila) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['id_triangulo']); ?></td>
                                <td><?php echo htmlspecialchars($fila['cateto_a']); ?></td>
                                <td><?php echo htmlspecialchars($fila['cateto_b']); ?></td>
                                <td><?php echo htmlspecialchars($fila['hipotenusa']); ?></td>
                                <td><?php echo htmlspecialchars($fila['detalle']); ?></td>
                                <td><?php echo htmlspecialchars($fila['area']); ?></td>
                                <td><?php echo htmlspecialchars($fila['perimetro']); ?></td>
                                <td><?php echo htmlspecialchars($fila['fecha_registro']); ?></td>
                                <td class="acciones">
                                    <a class="btn-tabla editar" href="home.php?editar=<?php echo (int) $fila['id_triangulo']; ?>">Editar</a>

                                    <form action="" method="post" onsubmit="return confirm('¿Desea eliminar este registro?');">
                                        <input type="hidden" name="id_triangulo" value="<?php echo (int) $fila['id_triangulo']; ?>">
                                        <button class="btn-tabla eliminar" type="submit" name="accion" value="eliminar">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="9">No hay registros para mostrar.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <script src="../assetes/js/app.js?v=2"></script>
</body>
</html>
