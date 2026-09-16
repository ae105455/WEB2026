<?php
    class ReporteModel{
        private $db;
        
        public function __construct(){
                $this->db = new PDO(
                    'mysql:host=localhost;dbname=dbventas;charset=utf8mb4',
                    'root',
                    '',
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    )
                );
        }

        public function getVentasPorFecha($fecha_inicio, $fecha_fin, $buscar = ''){
                $inicio = DateTime::createFromFormat('!Y-m-d', $fecha_inicio);
                $fin = DateTime::createFromFormat('!Y-m-d', $fecha_fin);

                if (!$inicio || !$fin || $inicio->format('Y-m-d') !== $fecha_inicio || $fin->format('Y-m-d') !== $fecha_fin) {
                    throw new InvalidArgumentException('El rango de fechas no es válido.');
                }

                if ($inicio > $fin) {
                    throw new InvalidArgumentException('La fecha inicial no puede ser posterior a la fecha final.');
                }

            $sql = "SELECT v.id AS venta_id,
                    v.fecha,
                    p.nombre AS producto,
                    dv.cantidad,
                    dv.precio_unitario,
                    (dv.cantidad * dv.precio_unitario) AS subtotal
                    FROM ventas v
                    INNER JOIN detalle_ventas dv ON v.id = dv.venta_id
                    INNER JOIN productos p ON dv.producto_id = p.id
                            WHERE v.fecha >= :inicio AND v.fecha <= :fin
                            AND (:buscar = '' OR p.nombre LIKE :buscar_nombre OR CAST(v.id AS CHAR) LIKE :buscar_id)
                    ORDER BY v.id ASC";
                    
            $stmt = $this->db->prepare($sql);
                    $stmt->bindValue(':inicio', $inicio->format('Y-m-d'), PDO::PARAM_STR);
                    $stmt->bindValue(':fin', $fin->format('Y-m-d'), PDO::PARAM_STR);
                            $stmt->bindValue(':buscar', trim($buscar), PDO::PARAM_STR);
                            $stmt->bindValue(':buscar_nombre', '%' . trim($buscar) . '%', PDO::PARAM_STR);
                            $stmt->bindValue(':buscar_id', '%' . trim($buscar) . '%', PDO::PARAM_STR);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>