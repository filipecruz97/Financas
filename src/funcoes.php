<?php 

function formatarMoeda ($valor) {
    return "R$ " . number_format($valor, 2, ",", ".");
}