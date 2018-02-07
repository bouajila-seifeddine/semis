<?php
include_once('tipsacarrier.php');
class AdminTipsa extends AdminTab
{
  public function __construct()
  {
    $this->className = 'AdminTipsa';
    parent::__construct();
  }

  public function display()
  {
    global $cookie;
    $manejador = new tipsacarrier();
    switch($_GET['option'])
    {
      case 'etiqueta': 
        echo $manejador->imprimirEtiquetas($_GET['id_order_envio']);
      break;
      case 'cancelar': 
        echo $manejador->cancelarEnvio($_GET['id_order_envio']);
      break;
      case 'envio': 
        echo $manejador->enviarEmailTrack($_GET['id_order_envio']);
      break;
      
      default:
        echo $manejador->pedidosTabla();
      break;
    }
  }
}