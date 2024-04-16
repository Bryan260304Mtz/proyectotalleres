<?php
    require_once "./core/handlers.php";

    require_once "./modules/modulos/Modulos.php";

     class IntranetController{

        
            function __construct($metodo, $parametros) {
        
                if (method_exists($this, $metodo)) {
                    call_user_func(array($this, $metodo), $parametros);
                } else {
                    echo "El metodo no existe " . $metodo;
                }
            }
        
            public function home() {               
                $menu = (new Modulos())->menu(122);
                escribeMatriz($menu);

                //$maestrosView = new MaestrosView();
                //$maestrosView->home($cursos, $persona);
      }
     }
?>