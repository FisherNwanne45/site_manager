<?php
class HostingController
{
    private $pdo;
    private $hostingModel;
    private $websiteModel;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
        $this->hostingModel = new Hosting($pdo);
        $this->websiteModel = new Website($pdo); // Initialize website model here
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        // Get all hosting plans with their complete data
        $hostingPlans = $this->hostingModel->getAllHostingPlans();

        // Get service counts separately
        $hostingWithCounts = $this->hostingModel->getHostingPlansWithServiceCounts();

        // Merge the service counts into the main hosting plans array
        foreach ($hostingPlans as &$plan) {
            foreach ($hostingWithCounts as $countPlan) {
                if ($plan['id'] == $countPlan['id']) {
                    $plan['service_count'] = $countPlan['service_count'];
                    break;
                }
            }
            // Ensure service_count is set even if no match found
            $plan['service_count'] = $plan['service_count'] ?? 0;
        }
        unset($plan); // Break the reference

        require APP_PATH . '/views/hosting/index.php';
    }

    public function create()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'server_name' => $_POST['server_name'],
                'provider' => $_POST['provider'] ?? null,
                'email_address' => $_POST['email_address'],
                'ip_address' => $_POST['ip_address'] ?? null,
            ];

            try {
                $this->hostingModel->createHostingPlan($data);
                $_SESSION['message'] = "Cliente creato con successo";
                header('Location: index.php?action=hosting');
                exit;
            } catch (InvalidArgumentException $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?action=hosting&do=create');
                exit;
            } catch (PDOException $e) {
                $_SESSION['error'] = "Errore durante la creazione del cliente: " . $e->getMessage();
                $_SESSION['form_data'] = $_POST;
                header('Location: index.php?action=hosting&do=create');
                exit;
            }
        }

        $formData = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_data']);

        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['error']);

        require APP_PATH . '/views/hosting/create.php';
    }

    public function edit($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $hostingPlan = $this->hostingModel->getHostingPlanById($id);
        if (!$hostingPlan) {
            header('Location: index.php?action=hosting');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'server_name' => $_POST['server_name'],
                'provider' => $_POST['provider'] ?? null,
                'email_address' => $_POST['email_address'] ?? null,
                'ip_address' => $_POST['ip_address'] ?? null,
            ];

            try {
                $this->hostingModel->updateHostingPlan($id, $data);
                $_SESSION['message'] = "Client aggiornato con successo";
                header('Location: index.php?action=hosting');
                exit;
            } catch (PDOException $e) {
                $error = "Errore durante l'aggiornamento del client: " . $e->getMessage();
            }
        }

        require APP_PATH . '/views/hosting/create.php';
    }

    public function delete($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $this->hostingModel->deleteHostingPlan($id);
        $_SESSION['message'] = "Client eliminato con successo";
        header('Location: index.php?action=hosting');
        exit;
    }

    public function services($hostingId)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        try {
            // Get hosting plan details
            $hostingPlan = $this->hostingModel->getHostingPlanById($hostingId);

            if (!$hostingPlan) {
                throw new Exception("Cliente non trovato");
            }

            // Get associated services
            $services = $this->websiteModel->getServicesByHostingId($hostingId);

            // Calculate dynamic status for each service
            foreach ($services as &$service) {
                $service['dynamic_status'] = $this->websiteModel->calculateDynamicStatus($service['expiry_date']);
            }
            unset($service); // Break the reference

            require APP_PATH . '/views/hosting/services.php';
        } catch (Exception $e) {
            $_SESSION['error'] = "Errore: " . $e->getMessage();
            header('Location: index.php?action=hosting');
            exit;
        }
    }
}
