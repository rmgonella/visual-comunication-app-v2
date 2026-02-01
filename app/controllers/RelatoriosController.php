<?php
/**
 * Controller de Relatórios
 */

class RelatoriosController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    /**
     * Exibir página de relatórios
     */
    public function index() {
        $this->render('relatorios/index');
    }
}
