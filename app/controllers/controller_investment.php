<?php
class Controller_Investment extends Controller {
    function __construct() {
        
        Session::start();
        if (!Session::is_logged_in()) {
            header('Location:/login/');
            exit();
        }

        $this->view = new View();
        $this->model = new Model_Profile();
    }
    function action_index()
    {
        header('Location:/investment/dashboard/');
        exit();
    }

    function action_dashboard()
    {
        $initialData = $this->model->get_initial_data();

        $this->view->generate('dashboard_view.php', 'template_view.php', $initialData);
    }

    function action_get_investments_for_pagination()
    {
        $pageToLoad = $_POST["pageToLoad"] ?? 1;

        $currentPageSpendings = $this->model->get_spendings(25, $pageToLoad);

        echo json_encode($currentPageSpendings, JSON_UNESCAPED_UNICODE);
    } 

    function action_investments()
    {
        $initialData = $this->model->get_initial_data();

        $this->view->generate('statistics_view.php', 'template_view.php', $initialData);
    }

    function action_sources()
    {
        $initialData = $this->model->get_initial_data();
        $this->view->generate('sources_view.php', 'template_view.php', $initialData);
    }

    function action_create_investment() {
    if (isset($_POST["new_source_name"])) {
        $new_source_name = $_POST["new_source_name"];
        $new_source_description = $_POST["new_source_description"];
        $result = $this->model->add_source($new_source_name, $new_source_description);
        echo json_encode($result, JSON_UNESCAPED_UNICODE);
    } else {
        header('Location:/test/');
    }
    }

    function action_delete_investment()
    {
        if (isset($_POST["source_to_delete_id"])) {
            $source_to_delete_id = $_POST["source_to_delete_id"];
            $result = $this->model->delete_source($source_to_delete_id);
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
        } else {
            header('Location:/site_security/');
        }
    }

    function action_get_currencies()
    {
        $initialData = $this->model->get_initial_data();

        $this->view->generate('settings_view.php', 'template_view.php', $initialData);
    }

    function action_security()
    {
        $initialData = $this->model->get_initial_data();

        $this->view->generate('security_view.php', 'template_view.php', $initialData);
    }
}