<?php 
    if(isset($_POST)){
    $data = file_get_contents("php://input");
    $user = json_decode($data, true);

    function isZero($n, $tol = 1.0e-8) {
        return abs($n) < $tol;
      }

    function priceTable($np, $pv, $t, $pmt, $dp) {
        $dataTable = [
          ["Mês", "Prestação", "Juros", "Amortização", "Saldo Devedor"],
        ];
        $pt = $dp ? $pmt : 0;
        $jt = 0;
        $at = 0;
        $dataTable[] = ["n", "R = pmt", "J = SD * t", "U = pmt - J", "SD = PV - U"];
        $dataTable[] = [0, $pt, "($t)", 0, $pv];
        if ($t <= 0) return $dataTable;
        for ($i = 0; $i < $np; ++$i) {
          $juros = $pv * $t;
          $amortizacao = $pmt - $juros;
          $saldo = $pv - $amortizacao;
          $pv = $saldo;
          $pt += $pmt;
          $jt += $juros;
          $at += $amortizacao;
          $dataTable[] = [$i + 1, $pmt, $juros, $amortizacao, isZero($saldo) ? 0 : $saldo];
        }
        $dataTable[] = ["Total", $pt, $jt, $at, 0];
        return $dataTable;
      }
    
    // do whatever we want with the users array.

    // echo print_r(priceTable($user["np"], $user["pv"], $user["t"], $user["pmt"], $user["dp"]), true);
    // echo priceTable($user["np"], $user["pv"], $user["t"], $user["pmt"], $user["dp"]);
    echo json_encode(priceTable($user["np"], $user["pv"], $user["t"], $user["pmt"], $user["dp"]));

    }

?>