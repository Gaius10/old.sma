<?php
use mvc\MainController;
/**
 * Classe responsável por controlar a página de gerenciamento de almocos
 * 
 * @author Caio Corrêa Chaves
 */
class AlmocoController extends MainController
{
    /**
     * $periodo
     * 
     * Receberá dado referente ao periodo do qual os almocos serao mostrados
     * 
     * @var string
     * @access private
     */
    private $periodo;

    /**
     * $alunos
     * 
     * Receberá os dados dos alunos que almoçaram no dia gerenciado
     * 
     * @var array|null
     * @access private
     */
    private $alunos;

    /**
     * $infos
     * 
     * Receberá informações adicionais a respeito do(s) almoço gerenciado(s)
     * 
     * @var array
     * @access private
     */
    private $infos;

    /**
     * $almocos
     * 
     * Receberá os dados dos almocos gerenciados
     * 
     * @var array
     * @access private
     */
    private $almocos;

    /**
     * function index()
     * 
     * Mostra os dados do almoco em questão
     * 
     * @param array $date Data do almoco a ser gerenciado
     * 
     * @access public
     * @return void
     */
    public function index($date = null)
    {
        ## Ver se usuário está logado
        if (!$this->loggedIn) {
            $this->logout(true);
        } else {
            // Se uma pagina foi solicitada antes do ato do login
            if (isset($_SESSION['gotoUrl'])) {
                $this->gotoPage($_SESSION['gotoUrl']);
                exit();
            }

            // Processar dados do almoco
            $date = ($date) ? $date[0] : date('Y-m-d');
            $this->model  = $this->loadModel('almoco/Almoco');
            $this->infos  = $this->model->loadInfo($date);
            
            if (!empty($this->infos)) {
                $this->alunos = $this->model->loadAlunos($this->infos['cod']);
            }

            // Mostrar dados ao usuário
            $this->title = 'Bem vindo ao SMA';
            $pag = "";
            $styleRequires = [
                'menu',
                'almoco',
                'modal',
                'footer',
                // modals
                'modal/encomenda',
                'modal/novo-monitor',
                'modal/iniciar-almoco',
                'modal/meus-dados',
                'modal/confirmacao',
                'modal/ocorrencia',
                'modal/trocar-adm'
            ];

            include VIEWS_PATH . '/_includes/header.php';
            include VIEWS_PATH . '/_includes/menu.php';
            include VIEWS_PATH . '/almoco.view.php';
            include VIEWS_PATH . '/_includes/footer.php';
        }
    }

    /**
     * function gerenciar()
     * 
     * Mostra todos os almocos já registrados e seus dados
     * 
     * @param array|string $date Data ou perído do(s) almoço(s) a ser(em) 
     *                           gerenciado(s)
     * 
     * @access public
     * @return void
     */
    public function gerenciar($date = null)
    {
        if (!$this->loggedIn) {
            $this->logout(true);
        } else {
            // Se uma pagina foi solicitada antes do ato do login
            if (isset($_SESSION['gotoUrl'])) {
                $this->gotoPage($_SESSION['gotoUrl']);
                exit();
            }

            if ($date) {
                $this->index($date);
            } else {
                // Buscar almocos
                $this->almocos = $this->loadModel('almoco/GerenciarAlmocos');
                $this->infos   = $this->almocos->getInfos();
                $this->almocos = $this->almocos->getAlmocos();

                // Para sinalizar no menu
                $pag = "ger_alm";
                $ver  = !empty($_GET['view'])   ? $_GET['view'] : 'all';
                $ver2 = !empty($_GET['global']) ? $_GET['global'] : 'all';


                $styleRequires = [
                    'menu',
                    'gerenciar-almocos',
                    'modal',
                    'footer',
                    // modals
                    'modal/encomenda',
                    'modal/novo-monitor',
                    'modal/iniciar-almoco',
                    'modal/meus-dados',
                    'modal/confirmacao',
                    'modal/ocorrencia',
                    'modal/trocar-adm'
                ];

                include VIEWS_PATH . "/_includes/header.php";
                include VIEWS_PATH . "/_includes/menu.php";
                include VIEWS_PATH . '/gerenciar-almocos.view.php';
                include VIEWS_PATH . "/_includes/footer.php";
            }
        }
    }
}
