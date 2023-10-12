<?php
session_start();
include_once "../db_conecta_adodb.inc.php";
//die(print_r($_GET));
//print_r($_GET);
$descripcion = $_GET['term'];
$casino      = $_GET['casino'];
$variables   = array();
$variables[] = $descripcion;
$variables[] = $descripcion;

if (isset($_GET['casino']) && (int) $_GET['casino'] != 0 && (int) $_GET['casino'] != 100) {
    $variables[]      = $casino;
    $condicion_casino = "AND A.ID_CASINO = ?";
}
$db->debug = true;
try {
    $rs = $db->Execute("SELECT *
                                FROM (
                                      SELECT MAX(A.ID_CLIENTE) AS CODIGO,
                                        INITCAP(A.APELLIDO)
                                        || ' '
                                        || INITCAP(A.NOMBRE) AS DESCRIPCION
                                      FROM LAVADO_DINERO.T_CLIENTE A,
                                        CASINO.T_CASINOS B
                                      WHERE A.ID_CASINO   =B.ID_CASINO
                                      AND A.FECHA_BAJA   IS NULL
                                      AND A.USUARIO_BAJA IS NULL
                                      AND (lower(A.NOMBRE) LIKE  lower(''||?||'%')
                                      OR lower(A.APELLIDO) LIKE  lower(''||?||'%'))
                                      $condicion_casino
                                      GROUP BY INITCAP(A.APELLIDO)
                                        || ' '
                                        || INITCAP(A.NOMBRE)
                                      )
                                WHERE ROWNUM<=15", $variables);
    $valores = array();
    while ($row = $rs->FetchNextObject($toupper = true)) {
        $valores[] = array('id' => $row->CODIGO, 'label' => $row->DESCRIPCION, 'value' => $row->DESCRIPCION);
    }
    header('Content-Type: application/json');
    echo json_encode($valores);
} catch (exception $e) {
    die($db->ErrorMsg());
}
