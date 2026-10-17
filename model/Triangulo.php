<?php
require_once __DIR__ . '/../config/Database.php';

class Triangulo
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->conectar();
    }

    public function listar(string $buscar = ''): array
    {
        if ($buscar !== '') {
            $sql = "SELECT * FROM triangulo
                    WHERE detalle LIKE :buscarDetalle
                       OR CAST(cateto_a AS CHAR) LIKE :buscarCatetoA
                       OR CAST(cateto_b AS CHAR) LIKE :buscarCatetoB
                       OR CAST(hipotenusa AS CHAR) LIKE :buscarHipotenusa
                    ORDER BY id_triangulo DESC";
            $stmt = $this->pdo->prepare($sql);

            $valorBuscar = '%' . $buscar . '%';

            $stmt->execute([
                'buscarDetalle' => $valorBuscar,
                'buscarCatetoA' => $valorBuscar,
                'buscarCatetoB' => $valorBuscar,
                'buscarHipotenusa' => $valorBuscar,
            ]);

            return $stmt->fetchAll();
        }

        $sql = "SELECT * FROM triangulo ORDER BY id_triangulo DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM triangulo WHERE id_triangulo = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $registro = $stmt->fetch();

        return $registro ?: null;
    }

    public function guardar(string $detalle, float $catetoA, float $catetoB): bool
    {
        $hipotenusa = sqrt(pow($catetoA, 2) + pow($catetoB, 2));
        $area = ($catetoA * $catetoB) / 2;
        $perimetro = $catetoA + $catetoB + $hipotenusa;

        $sql = "INSERT INTO triangulo(detalle, cateto_a, cateto_b, hipotenusa, area, perimetro)
                VALUES(:detalle, :cateto_a, :cateto_b, :hipotenusa, :area, :perimetro)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'detalle' => $detalle,
            'cateto_a' => $catetoA,
            'cateto_b' => $catetoB,
            'hipotenusa' => $hipotenusa,
            'area' => $area,
            'perimetro' => $perimetro,
        ]);
    }

    public function actualizar(int $id, string $detalle, float $catetoA, float $catetoB): bool
    {
        $hipotenusa = sqrt(pow($catetoA, 2) + pow($catetoB, 2));
        $area = ($catetoA * $catetoB) / 2;
        $perimetro = $catetoA + $catetoB + $hipotenusa;

        $sql = "UPDATE triangulo
                SET detalle = :detalle,
                    cateto_a = :cateto_a,
                    cateto_b = :cateto_b,
                    hipotenusa = :hipotenusa,
                    area = :area,
                    perimetro = :perimetro
                WHERE id_triangulo = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'detalle' => $detalle,
            'cateto_a' => $catetoA,
            'cateto_b' => $catetoB,
            'hipotenusa' => $hipotenusa,
            'area' => $area,
            'perimetro' => $perimetro,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM triangulo WHERE id_triangulo = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
