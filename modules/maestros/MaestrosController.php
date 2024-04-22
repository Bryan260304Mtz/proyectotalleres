<?php

    require_once "./core/handlers.php";
    require_once "MaestrosView.php";
    require_once "MaestrosModel.php";
    require_once "./modules/persona/PersonaModel.php";

     class MaestrosController{

            private $view;
            private $model;
        
            function __construct($metodo, $parametros) {
        
                if (method_exists($this, $metodo)) {
                    call_user_func(array($this, $metodo), $parametros);
                } else {
                    echo "El metodo no existe " . $metodo;
                }
            }
        
            public function home() {
                
 
                $personaModel = new PersonaModel();
                $persona = $personaModel->darPersona(25);
                //escribeMatriz($persona);
 
                $maestrosView = new MaestrosView();
                $maestrosView->home($persona);
                
      }
      public function tallerista()
		{
            $maestrosModel = new MaestrosModel();
			$docente = $maestrosModel->darTallerista();

            $personaModel = new PersonaModel();
			$persona = $personaModel->darPersona(5);

			$maestrosView = new MaestrosView();
			$maestrosView->tallerista($persona, $docente);
		}
     }
?>