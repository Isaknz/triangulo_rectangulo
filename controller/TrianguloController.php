<?php
require_once __DIR__ . '/../model/Triangulo.php';

class TrianguloController
{
    private Triangulo $modelo;

    public function __construct()
    {
        $this->modelo = new Triangulo();
    }

    public function procesarFormulario(): string
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return '';
        }

        $accion = $_POST['accion'] ?? '';

        if ($accion === 'guardar') {
            return $this->guardarOActualizar();
        }

        if ($accion === 'eliminar') {
            return $this->eliminar();
        }

        return '';
    }

    private function guardarOActualizar(): string
    {
        $id = (int) ($_POST['id_triangulo'] ?? 0);
        $detalle = trim($_POST['txtDetalle'] ?? '');
        $catetoA = trim($_POST['txtCatetoA'] ?? '');
        $catetoB = trim($_POST['txtCatetoB'] ?? '');

        if ($catetoA === '' || !is_numeric($catetoA) || (float) $catetoA <= 0) {
            return 'Ingrese un cateto A válido mayor a cero';
        }

        if ($catetoB === '' || !is_numeric($catetoB) || (float) $catetoB <= 0) {
            return 'Ingrese un cateto B válido mayor a cero';
        }

        if ($id > 0) {
            $this->modelo->actualizar($id, $detalle, (float) $catetoA, (float) $catetoB);
            header('Location: home.php?mensaje=actualizado');
            exit;
        }

        $this->modelo->guardar($detalle, (float) $catetoA, (float) $catetoB);
        header('Location: home.php?mensaje=guardado');
        exit;
    }

    private function eliminar(): string
    {
        $id = (int) ($_POST['id_triangulo'] ?? 0);

        if ($id <= 0) {
            return 'No se pudo eliminar el registro';
        }

        $this->modelo->eliminar($id);
        header('Location: home.php?mensaje=eliminado');
        exit;
    }

    public function listar(string $buscar = ''): array
    {
        return $this->modelo->listar($buscar);
    }

    public function obtenerParaEditar(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        return $this->modelo->obtenerPorId($id);
    }
}
