<?php

namespace MVC\Core;

class Controller
{
    /**
     * Constroi o router das URIs
     * Baseado no htaccess
     * Redireciona para as urls > Controller
     */
    public function init()
    {
        if (isset($_GET['q'])) {
            $this->router($_GET['q']);
            return true;
        }
        $this->redirect('site');
    }

    protected function router($get)
    {
        $routes = explode('/', $get);
        $ctrl = 'MVC\\Controllers\\' . ucfirst($routes[0]);
        $ctrl .= 'Controller';

        $count = count($routes);

        if (!class_exists($ctrl)) {
            exit('<h1>ERRO 404 Pagina nao encontrada</h1>');
        }
        $controller = new $ctrl;

        switch ($count) {
            case 1:
                $controller->indexAction();
                break;
            case 2:
                $action = $routes[1] . 'Action';
                $this->action_exits($controller, $action);
                $controller->$action();
                break;
            default:
                $action = $routes[1] . 'Action';
                $this->action_exits($controller, $action);
                // Retira os 2 primeiros parametros controller/action
                $params = array_slice($routes, 2);
                // Chama o método da classe controller com seus parametros
                // Colocado na url
                call_user_func_array([$controller, $action], $params);
        }
    }

    protected function redirect($uri) {
        header("Location: $uri");
    }

    protected function action_exits($object, $method_name)
    {
        if (!method_exists($object, $method_name)) {
            exit("<h1>ERRO 505 Action '$method_name' inexistente</h1>");
        }
    }
}